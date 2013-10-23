<?php

/**
 * @file
 * Contains \Drupal\devel_generate\Form\GenerateContent.
 */

namespace Drupal\devel_generate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Language\Language;

/**
 * Defines a form that allows privileged users to generate nodes and comments.
 */
class GenerateContent extends FormBase implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_generate_content_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array(
      '#title' => t('Generate content'),
      '#description' => t('Generate a given number of nodes and comments. Optionally delete current items.'),
    );

    $options = array();

    if (module_exists('content')) {
      $types = content_types();
      foreach ($types as $type) {
        $warn = '';
        if (count($type['fields'])) {
          $warn = t('. This type contains CCK fields which will only be populated by fields that implement the content_generate hook.');
        }
        $options[$type['type']] = array('#markup' => t($type['name']). $warn);
      }
    }
    else {
      $types = node_type_get_types();
      foreach ($types as $type) {
        $options[$type->type] = array(
          'type' => array('#markup' => t($type->name)),
        );
        if (module_exists('comment')) {
          $default = variable_get('comment_' . $type->type, COMMENT_OPEN);
          $map = array(t('Hidden'), t('Closed'), t('Open'));
          $options[$type->type]['comments'] = array('#markup' => '<small>'. $map[$default]. '</small>');
        }
      }
    }
    // we cannot currently generate valid polls.
    unset($options['poll']);

    if (empty($options)) {
      drupal_set_message(t('You do not have any content types that can be generated. <a href="@create-type">Go create a new content type</a> already!</a>', array('@create-type' => url('admin/structure/types/add'))), 'error', FALSE);
      return;
    }

    $header = array(
      'type' => t('Content type'),
    );
    if (module_exists('comment')) {
      $header['comments'] = t('Comments');
    }

    $form['node_types'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#tableselect' => TRUE,
    );

    $form['node_types'] += $options;

    if (module_exists('checkall')) $form['node_types']['#checkall'] = TRUE;
    $form['kill_content'] = array(
      '#type' => 'checkbox',
      '#title' => t('<strong>Delete all content</strong> in these content types before generating new content.'),
      '#default_value' => FALSE,
    );
    $form['num_nodes'] = array(
      '#type' => 'textfield',
      '#title' => t('How many nodes would you like to generate?'),
      '#default_value' => 50,
      '#size' => 10,
    );

    $options = array(1 => t('Now'));
    foreach (array(3600, 86400, 604800, 2592000, 31536000) as $interval) {
      $options[$interval] = format_interval($interval, 1) . ' ' . t('ago');
    }
    $form['time_range'] = array(
      '#type' => 'select',
      '#title' => t('How far back in time should the nodes be dated?'),
      '#description' => t('Node creation dates will be distributed randomly from the current time, back to the selected time.'),
      '#options' => $options,
      '#default_value' => 604800,
    );

    $form['max_comments'] = array(
      '#type' => module_exists('comment') ? 'textfield' : 'value',
      '#title' => t('Maximum number of comments per node.'),
      '#description' => t('You must also enable comments for the content types you are generating. Note that some nodes will randomly receive zero comments. Some will receive the max.'),
      '#default_value' => 0,
      '#size' => 3,
      '#access' => module_exists('comment'),
    );
    $form['title_length'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum number of words in titles'),
      '#default_value' => 4,
      '#size' => 10,
    );
    $form['add_alias'] = array(
      '#type' => 'checkbox',
      '#disabled' => !module_exists('path'),
      '#description' => t('Requires path.module'),
      '#title' => t('Add an url alias for each node.'),
      '#default_value' => FALSE,
    );
    $form['add_statistics'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add statistics for each node (node_counter table).'),
      '#default_value' => TRUE,
      '#access' => module_exists('statistics'),
    );

    unset($options);
    $options[Language::LANGCODE_NOT_SPECIFIED] = t('Language neutral');
    if (module_exists('locale')) {
      $languages = language_list();
      foreach ($languages as $langcode => $language) {
        $options[$langcode] = $language->name;
      }
    }
    $form['add_language'] = array(
      '#type' => 'select',
      '#title' => t('Set language on nodes'),
      '#multiple' => TRUE,
      '#disabled' => !module_exists('locale'),
      '#description' => t('Requires locale.module'),
      '#options' => $options,
      '#default_value' => array(Language::LANGCODE_NOT_SPECIFIED),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Generate'),
      '#tableselect' => TRUE,
    );
    $form['#redirect'] = FALSE;


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    module_load_include('inc', 'devel_generate', 'devel_generate');
    if ($form_state['values']['num_nodes'] <= 50 && $form_state['values']['max_comments'] <= 10) {
      module_load_include('inc', 'devel_generate');
      devel_generate_content($form_state);
    }
    else {
      module_load_include('inc', 'devel_generate', 'devel_generate_batch');
      devel_generate_batch_content($form_state);
    }
  }

}
