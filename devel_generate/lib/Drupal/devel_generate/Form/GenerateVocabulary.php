<?php

/**
 * @file
 * Contains \Drupal\devel_generate\Form\GenerateVocabulary.
 */

namespace Drupal\devel_generate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;

/**
 * Defines a form that allows privileged users to generate vocabularies.
 */
class GenerateVocabulary extends FormBase implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_generate_vocab_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array(
      '#title' => t('Generate vocabularies'),
      '#description' => t('Generate a given number of vocabularies. Optionally delete current vocabularies.'),
    );

    $form['num_vocabs'] = array(
      '#type' => 'textfield',
      '#title' => t('Number of vocabularies?'),
      '#default_value' => 1,
      '#size' => 10,
    );
    $form['title_length'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum number of characters in vocabulary names'),
      '#default_value' => 12,
      '#size' => 10,
    );
    $form['kill_taxonomy'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete existing vocabularies before generating new ones.'),
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
      devel_generate_delete_vocabularies();
      drupal_set_message(t('Deleted existing vocabularies.'));
    }
    $new_vocs = devel_generate_vocabs($values['num_vocabs'], $values['title_length']);
    if (!empty($new_vocs)) {
      drupal_set_message(t('Created the following new vocabularies: !vocs', array('!vocs' => implode(', ', $new_vocs))));
    }
  }

}
