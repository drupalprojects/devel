<?php

namespace Drupal\devel_generate;

class DevelGenerateFieldNumber extends DevelGenerateFieldBase {

  public function generateValues($object, $instance, $plugin_definition, $form_display_options) {
    $object_field = array();
    $settings = $instance->getFieldSettings();

    // Make sure the instance settings are all set.
    foreach (array('min', 'max', 'precision', 'scale') as $key) {
      if (empty($settings[$key])) {
        $settings[$key] = NULL;
      }
    }
    $min = is_numeric($settings['min']) ? $settings['min'] : 0;
    switch ($form_display_options['type']) {
      case 'number_integer':
        $max = is_numeric($settings['max']) ? $settings['max'] : 10000;
        $decimal = 0;
        $scale = 0;
        break;

      case 'number_decimal':
        $precision = is_numeric($settings['precision']) ? $settings['precision'] : 10;
        $scale = is_numeric($settings['scale']) ? $settings['scale'] : 2;
        $max = is_numeric($settings['max']) ? $settings['max'] : pow(10, ($precision - $scale));
        $decimal = rand(0, (10 * $scale)) / 100;
        break;

      case 'number_float':
        $precision = rand(10, 32);
        $scale = rand(0, 2);
        $decimal = rand(0, (10 * $scale)) / 100;
        $max = is_numeric($settings['max']) ? $settings['max'] : pow(10, ($precision - $scale));
        break;
    }
    $object_field['value'] = round((rand($min, $max) + $decimal), $scale);
    return $object_field;
  }

}
