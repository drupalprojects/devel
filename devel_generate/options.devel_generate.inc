<?php

use Drupal\field\Field;

function options_devel_generate($object, $instance, $plugin_definition, $form_display_options) {
  if (isset($plugin_definition['multiple_values']) && $plugin_definition['multiple_values'] === TRUE) {
    return devel_generate_multiple('_options_devel_generate', $object, $instance, $plugin_definition, $form_display_options);
  }
  else {
    return _options_devel_generate($object, $instance, $plugin_definition, $form_display_options);
  }
}

function _options_devel_generate($object, $instance, $plugin_definition, $form_display_options) {
  $object_field = array();
  $field_info = Field::fieldInfo()->getField($object->entityType(), $instance->getName());
  if ($allowed_values = options_allowed_values($field_info, $object)) {
    $keys = array_keys($allowed_values);
    $object_field['value'] = $keys[mt_rand(0, count($allowed_values) - 1)];
  }
  return $object_field;
}
