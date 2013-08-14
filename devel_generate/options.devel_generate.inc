<?php
// Id$

function options_devel_generate($object, $field, $instance, $bundle) {
  if (field_behaviors_widget('multiple values', $instance) == FIELD_BEHAVIOR_CUSTOM) {
    return devel_generate_multiple('_list_devel_generate', $object, $field, $instance, $bundle);
  }
  else {
    return _options_devel_generate($object, $field, $instance, $bundle);
  }
}

function _options_devel_generate($object, $field, $instance, $bundle) {
  $object_field = array();
  if ($allowed_values = options_allowed_values($field, $object)) {
    $keys = array_keys($allowed_values);
    $object_field['value'] = $keys[mt_rand(0, count($allowed_values) - 1)];
  }
  return $object_field;
}
