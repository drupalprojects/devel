<?php

/**
 * @file
 * Contains \Drupal\devel\Form\VariableEditForm.
 */

namespace Drupal\devel\Form;

use Drupal\Core\Form\FormBase;

/**
 * Provides a form to edit variables.
 */
class VariableEditForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_variable_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state, $name = '') {
    $value = variable_get($name, 'not found');
    $form['name'] = array(
      '#type' => 'value',
      '#value' => $name
    );
    $form['value'] = array(
      '#type' => 'item',
      '#title' => t('Old value'),
      '#markup' => dpr($value, TRUE),
    );
    if (is_string($value) || is_numeric($value)) {
      $form['new'] = array(
        '#type' => 'textarea',
        '#title' => t('New value'),
        '#default_value' => $value
      );
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
      );
    }
    else {
      $api = $this->config('devel.settings')->get('api_url', 'api.drupal.org');
      $form['new'] = array(
        '#type' => 'item',
        '#title' => t('New value'),
        '#markup' => t('Sorry, complex variable types may not be edited yet. Use the <em>Execute PHP</em> block and the <a href="@variable-set-doc">variable_set()</a> function.', array('@variable-set-doc' => "http://$api/api/HEAD/function/variable_set"))
      );
    }
    drupal_set_title($name);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    variable_set($form_state['values']['name'], $form_state['values']['new']);
    drupal_set_message(t('Saved new value for %name.', array('%name' => $form_state['values']['name'])));
  }

}
