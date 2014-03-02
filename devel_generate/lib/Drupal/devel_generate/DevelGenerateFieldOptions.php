<?php

namespace Drupal\devel_generate;

use Drupal\field\Field;

class DevelGenerateFieldOptions extends DevelGenerateFieldBase {

  public function generateValues($object, $instance, $plugin_definition, $form_display_options) {
    $object_field = array();
    $field_info = Field::fieldInfo()->getField($object->entityType(), $instance->getFieldName());
    if ($allowed_values = options_allowed_values($field_info, $object)) {
      $keys = array_keys($allowed_values);
      $object_field['value'] = $keys[mt_rand(0, count($allowed_values) - 1)];
    }
    return $object_field;
  }

}
