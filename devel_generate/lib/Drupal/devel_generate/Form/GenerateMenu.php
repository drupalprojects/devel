<?php

/**
 * @file
 * Contains \Drupal\devel_generate\Form\GenerateMenu.
 */

namespace Drupal\devel_generate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;

/**
 * Defines a form that allows privileged users to generate menus and menu links.
 */
class GenerateMenu extends FormBase implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_generate_menu_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array(
      '#title' => $this->t('Generate menus'),
      '#description' => $this->t('Generate a given number of menus and menu links. Optionally delete current menus.'),
    );

    $menu_enabled = module_exists('menu');
    if ($menu_enabled) {
      $menus = array('__new-menu__' => t('Create new menu(s)')) + menu_get_menus();
    }
    else {
      $menus = menu_list_system_menus();
    }
    $form['existing_menus'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Generate links for these menus'),
      '#options' => $menus,
      '#default_value' => array('__new-menu__'),
      '#required' => TRUE,
    );
    if ($menu_enabled) {
      $form['num_menus'] = array(
        '#type' => 'textfield',
        '#title' => t('Number of new menus to create'),
        '#default_value' => 2,
        '#size' => 10,
        '#states' => array(
          'visible' => array(
            ':input[name=existing_menus[__new-menu__]]' => array('checked' => TRUE),
          ),
        ),
      );
    }
    $form['num_links'] = array(
      '#type' => 'textfield',
      '#title' => t('Number of links to generate'),
      '#default_value' => 50,
      '#size' => 10,
      '#required' => TRUE,
    );
    $form['title_length'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum number of characters in menu and menu link names'),
      '#description' => t("The minimum length is 2."),
      '#default_value' => 12,
      '#size' => 10,
      '#required' => TRUE,
    );
    $form['link_types'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Types of links to generate'),
      '#options' => array(
        'node' => t('Nodes'),
        'front' => t('Front page'),
        'external' => t('External'),
      ),
      '#default_value' => array('node', 'front', 'external'),
      '#required' => TRUE,
    );
    $form['max_depth'] = array(
      '#type' => 'select',
      '#title' => t('Maximum link depth'),
      '#options' => range(0, MENU_MAX_DEPTH),
      '#default_value' => floor(MENU_MAX_DEPTH / 2),
      '#required' => TRUE,
    );
    unset($form['max_depth']['#options'][0]);
    $form['max_width'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum menu width'),
      '#default_value' => 6,
      '#size' => 10,
      '#description' => t("Limit the width of the generated menu's first level of links to a certain number of items."),
      '#required' => TRUE,
    );
    $form['kill'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete existing custom generated menus and menu links before generating new ones.'),
      '#default_value' => FALSE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Generate'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    // If the create new menus checkbox is off, set the number of new menus to 0.
    if (!isset($form_state['values']['existing_menus']['__new-menu__']) || !$form_state['values']['existing_menus']['__new-menu__']) {
      $form_state['values']['num_menus'] = 0;
    }
    else {
      // Unset the aux menu to avoid attach menu new items.
      unset($form_state['values']['existing_menus']['__new-menu__']);
    }
    module_load_include('inc', 'devel_generate');
    // Delete custom menus.
    if ($form_state['values']['kill']) {
      devel_generate_delete_menus();
      drupal_set_message(t('Deleted existing menus and links.'));
    }

    // Generate new menus.
    $new_menus = devel_generate_menus($form_state['values']['num_menus'], $form_state['values']['title_length']);
    if (!empty($new_menus)) {
      drupal_set_message(t('Created the following new menus: !menus', array('!menus' => implode(', ', $new_menus))));
    }

    // Generate new menu links.
    $menus = $new_menus + $form_state['values']['existing_menus'];
    $new_links = devel_generate_links($form_state['values']['num_links'], $menus, $form_state['values']['title_length'], $form_state['values']['link_types'], $form_state['values']['max_depth'], $form_state['values']['max_width']);
    drupal_set_message(t('Created @count new menu links.', array('@count' => count($new_links))));
  }

}
