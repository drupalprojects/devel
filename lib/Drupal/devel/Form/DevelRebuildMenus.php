<?php

/**
 * @file
 * Contains \Drupal\environment_indicator\Form\DevelRebuildMenus.
 */

namespace Drupal\devel\Form;

use Drupal\Core\Form\ConfirmFormBase;

/**
 * Provides a deletion confirmation form for devel_menu_rebuild.
 */
class DevelRebuildMenus extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'devel_menu_rebuild';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to rebuild menus?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelRoute() {
    return array('route_name' => 'devel.menu_rebuild');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Rebuild menu based on hook_menu() and revert any custom changes. All menu items return to their default settings.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Rebuild');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    menu_router_rebuild();
    drupal_set_message(t('The menu router has been rebuilt.'));
    $form_state['redirect'] = '<front>';
  }

}
