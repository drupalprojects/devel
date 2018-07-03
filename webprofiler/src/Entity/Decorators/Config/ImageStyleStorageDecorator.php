<?php

namespace Drupal\webprofiler\Entity\Decorators\Config;

use Drupal\Core\Session\AccountInterface;
use Drupal\image\ImageStyleStorageInterface;
use Drupal\shortcut\ShortcutSetInterface;
use Drupal\shortcut\ShortcutSetStorageInterface;

/**
 * Class ImageStyleStorageDecorator
 */
class ImageStyleStorageDecorator extends ConfigEntityStorageDecorator implements ImageStyleStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function setReplacementId($name, $replacement) {
    return $this->getOriginalObject()->setReplacementId($name, $replacement);
  }

  /**
   * {@inheritdoc}
   */
  public function getReplacementId($name) {
    return $this->getOriginalObject()->getReplacementId($name);
  }

  /**
   * {@inheritdoc}
   */
  public function clearReplacementId($name) {
    return $this->getOriginalObject()->clearReplacementId($name);
  }

}
