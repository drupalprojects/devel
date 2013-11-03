<?php

/**
 * @file
 * Contains \Drupal\devel\Form\VariableForm.
 */

namespace Drupal\devel\Form;

use Drupal\Core\Form\FormBase;

/**
 * Provides a form to choose variables to edit.
 */
class VariableForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_variable_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $header = array(
      'name' => array('data' => t('Name'), 'field' => 'name', 'sort' => 'asc'),
      'value' => array('data' => t('Value'), 'field' => 'value'),
      'length' => array('data' => t('Length'), 'field' => 'length'),
      'edit' => array('data' => t('Operations')),
    );

    $form['variables'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#empty' => t('No variables.'),
      '#tableselect' => TRUE,
    );

    // TODO: we could get variables out of $conf but that would include hard coded ones too. ideally i would highlight overridden/hard coded variables
    $query = db_select('variable', 'v')->extend('Drupal\Core\Database\Query\TableSortExtender');
    $query->fields('v', array('name', 'value'));
    switch (db_driver()) {
      case 'mssql':
        $query->addExpression("LEN(v.value)", 'length');
        break;
      default:
        $query->addExpression("LENGTH(v.value)", 'length');
        break;
    }
    $result = $query
      ->orderByHeader($header)
      ->execute();

    foreach ($result as $row) {
      $form['variables'][$row->name]['name'] = array('#markup' => check_plain($row->name));
      if (merits_krumo($row->value)) {
        $value = krumo_ob(variable_get($row->name, NULL));
      }
      else {
        if (drupal_strlen($row->value) > 70) {
          $value = check_plain(drupal_substr($row->value, 0, 65)) . '...';
        }
        else {
          $value = check_plain($row->value);
        }
      }
      $form['variables'][$row->name]['value'] = array('#markup' => $value);
      $form['variables'][$row->name]['length'] = array('#markup' => $row->length);
      $form['variables'][$row->name]['edit'] = array('#markup' => l(t('Edit'), "devel/variable/edit/$row->name"));
    }

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#tableselect' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $deletes = array_filter($form_state['values']['variables']);
    array_walk($deletes, 'variable_del');
    if (count($deletes)) {
      drupal_set_message(format_plural(count($deletes), 'One variable deleted.', '@count variables deleted.'));
    }
  }

}
