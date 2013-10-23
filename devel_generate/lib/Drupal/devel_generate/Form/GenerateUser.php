<?php

/**
 * @file
 * Contains \Drupal\devel_generate\Form\GenerateUser.
 */

namespace Drupal\devel_generate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;

/**
 * Defines a form that allows privileged users to generate users.
 */
class GenerateUser extends FormBase implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_generate_users_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array(
      '#title' => $this->t('Generate users'),
      '#description' => $this->t('Generate a given number of users. Optionally delete current users.'),
    );

    $form['num'] = array(
      '#type' => 'textfield',
      '#title' => t('How many users would you like to generate?'),
      '#default_value' => 50,
      '#size' => 10,
    );
    $form['kill_users'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete all users (except user id 1) before generating new users.'),
      '#default_value' => FALSE,
    );
    $options = user_role_names(TRUE);
    unset($options[DRUPAL_AUTHENTICATED_RID]);
    $form['roles'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Which roles should the users receive?'),
      '#description' => t('Users always receive the <em>authenticated user</em> role.'),
      '#options' => $options,
    );
    $form['pass'] = array(
      '#type' => 'textfield',
      '#title' => t('Password to be set'),
      '#default_value' => NULL,
      '#size' => 32,
      '#description' => t('Leave this field empty if you do not need to set a password'),
    );

    $options = array(1 => t('Now'));
    foreach (array(3600, 86400, 604800, 2592000, 31536000) as $interval) {
      $options[$interval] = format_interval($interval, 1) . ' ' . t('ago');
    }
    $form['time_range'] = array(
      '#type' => 'select',
      '#title' => t('How old should user accounts be?'),
      '#description' => t('User ages will be distributed randomly from the current time, back to the selected time.'),
      '#options' => $options,
      '#default_value' => 604800,
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
    module_load_include('inc', 'devel_generate');
    $values = $form_state['values'];
    devel_create_users($values['num'], $values['kill_users'], $values['time_range'], $values['roles'], $values['pass']);
  }

}
