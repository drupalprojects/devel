<?php
/**
* @file
* Contains \Drupal\devel\Plugin\Block\DevelExecutePHP.
*/

namespace Drupal\devel\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\block\Annotation\Block;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block for executing PHP code.
 *
 * @Block(
 *   id = "devel_execute_php",
 *   admin_label = @Translation("Execute PHP")
 * )
 */
class DevelExecutePHP extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    return $account->hasPermission('execute php code');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form_state = array();
    $form_state['build_info']['args'] = array();
    $form_state['build_info']['callback'] = array($this, 'executePhpForm');
    $form = drupal_build_form('devel_execute_block_form', $form_state);
    return array($form);
  }

  /**
   * Build the execute PHP block form.
   */
  public function executePhpForm() {
    $form['execute'] = array(
      '#type' => 'details',
      '#title' => t('Execute PHP Code'),
      '#collapsed' => (!isset($_SESSION['devel_execute_code'])),
    );
    $form['#submit'] = array('devel_execute_form_submit');
    return array_merge_recursive($form, devel_execute_form());
  }

}
