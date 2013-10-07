<?php

function text_devel_generate($object, $instance, $plugin_definition, $form_display_options) {
  if (isset($plugin_definition['multiple_values']) && $plugin_definition['multiple_values'] === TRUE) {
    return devel_generate_multiple('_text_devel_generate', $object, $instance, $plugin_definition, $form_display_options);
  }
  else {
    return _text_devel_generate($object, $instance, $plugin_definition, $form_display_options);
  }
}

function _text_devel_generate($object, $instance, $plugin_definition, $form_display_options) {
  $object_field = array();
  $settings = $instance->getFieldSettings();
  if (!empty($settings['text_processing'])) {
    $formats = filter_formats();
    $format = array_rand($formats);
  }
  else {
    $format = filter_fallback_format();
  }

  if (empty($settings['max_length'])) {
    // Textarea handling
    $object_field['value'] = devel_create_content($format);
    if ($form_display_options['type'] == 'text_textarea_with_summary' && !empty($settings['display_summary'])) {
      $object_field['summary'] = devel_create_content($format);
    }
  }
  else {
    // Textfield handling.
    $object_field['value'] = substr(devel_create_greeking(mt_rand(1, $settings['max_length'] / 6), FALSE), 0, $settings['max_length']);
  }
  $object_field['format'] = $format;
  return $object_field;
}
