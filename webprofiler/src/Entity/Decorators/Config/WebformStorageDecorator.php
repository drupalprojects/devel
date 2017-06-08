<?php

namespace Drupal\webprofiler\Entity\Decorators\Config;

use Drupal\webform\WebformEntityStorageInterface;

/**
 * Class WebformStorageDecorator
 */
class WebformStorageDecorator extends ConfigEntityStorageDecorator implements WebformEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getCategories($template = NULL) {
    return $this->getOriginalObject()->getCategories($template);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions($template = NULL) {
    return $this->getOriginalObject()->getOptions($template);
  }

}
