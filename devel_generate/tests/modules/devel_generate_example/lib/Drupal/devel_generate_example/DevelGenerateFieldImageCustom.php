<?php
/**
 * @file
 * Fake class for custom image generation business logic.
 */

namespace Drupal\devel_generate_example;

use Drupal\devel_generate\DevelGenerateFieldBase;

class DevelGenerateFieldImageCustom extends DevelGenerateFieldBase {

  function generateValues($object, $instance, $plugin_definition, $form_display_options) {
    $function = function_exists('drush_log') ? 'drush_log' : 'drupal_set_message';
    $function(t("Custom image field generation"));
  }

}
