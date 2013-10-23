<?php

/**
 * @file
 * Contains \Drupal\devel_generate\Form\GenerateTerm.
 */

namespace Drupal\devel_generate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;

/**
 * Defines a form that allows privileged users to generate terms.
 */
class GenerateTerm extends FormBase implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_generate_term_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array(
      '#title' => t('Generate terms'),
      '#description' => t('Generate a given number of terms. Optionally delete current terms.'),
    );

    $options = array();
    foreach (taxonomy_vocabulary_load_multiple() as $vid => $vocab) {
      $options[$vid] = $vocab->vid;
    }
    $form['vids'] = array(
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Vocabularies'),
      '#required' => TRUE,
      '#options' => $options,
      '#description' => t('Restrict terms to these vocabularies.'),
    );
    $form['num_terms'] = array(
      '#type' => 'textfield',
      '#title' => t('Number of terms?'),
      '#default_value' => 10,
      '#size' => 10,
    );
    $form['title_length'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum number of characters in term names'),
      '#default_value' => 12,
      '#size' => 10,
    );
    $form['kill_taxonomy'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete existing terms in specified vocabularies before generating new terms.'),
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
    $values = $form_state['values'];
    module_load_include('inc', 'devel_generate');
    if ($values['kill_taxonomy']) {
      foreach ($values['vids'] as $vid) {
        devel_generate_delete_vocabulary_terms($vid);
      }
      drupal_set_message(t('Deleted existing terms.'));
    }
    $vocabs = taxonomy_vocabulary_load_multiple($values['vids']);
    $new_terms = devel_generate_terms($values['num_terms'], $vocabs, $values['title_length']);
    if (!empty($new_terms)) {
      drupal_set_message(t('Created the following new terms: !terms', array('!terms' => implode(', ', $new_terms))));
    }
  }

}
