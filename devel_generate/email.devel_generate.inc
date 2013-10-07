<?php
/**
 * @file
 * Support file for generating email field content.
 */

function email_devel_generate($object, $instance, $plugin_definition, $form_display_options) {
  if (isset($plugin_definition['multiple_values']) && $plugin_definition['multiple_values'] === TRUE) {
    return devel_generate_multiple('_email_devel_generate', $object, $instance, $plugin_definition, $form_display_options);
  }
  else {
    return _email_devel_generate($object, $instance, $plugin_definition, $form_display_options);
  }
}

function _email_devel_generate($object, $instance, $plugin_definition, $form_display_options) {
  $object_field = array();

  // Set of possible top-level domains.
  $tlds = array('com', 'net', 'gov', 'org', 'edu', 'biz', 'info');

  // Set random lengths for the user and domain as the email field doesn't have
  // any setting for length.
  $user_length = mt_rand(5, 10);
  $domain_length = mt_rand(7, 15);

  $object_field['value'] = devel_generate_word($user_length) . '@' . devel_generate_word($domain_length) . '.' . $tlds[mt_rand(0, (sizeof($tlds)-1))];
  return $object_field;
}
