<?php

/**
 * @file
 * Contains \Drupal\devel_generate\DevelGenerateFieldBaseInterface.
 */

namespace Drupal\devel_generate;
use Drupal\field\Entity\FieldInstanceConfig;

/**
 * Base interface definition for generating fields for plugins functionality.
 *
 * This interface details base wrapping methods.
 * Most implementations will want to directly inherit generate()
 * from Drupal\devel_generate\DevelGenerateFieldBase.
 *
 */
interface DevelGenerateFieldBaseInterface {

  /**
   * Wrapper function for generateValues()which
   * most implementations will want to directly inherit
   * from Drupal\devel_generate\DevelGenerateFieldBase.
   *
   * @see generateFields().
   */
  public function generate($object, FieldInstanceConfig $instance, $plugin_definition, $form_display_options);

  /**
   * Business logic to add values to some field.
   */
  public function generateValues($object, FieldInstanceConfig $instance, $plugin_definition, $form_display_options);

}
