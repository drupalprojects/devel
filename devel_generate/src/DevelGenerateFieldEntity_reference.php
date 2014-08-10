<?php

namespace Drupal\devel_generate;

class DevelGenerateFieldEntity_reference extends DevelGenerateFieldBase {

  public function generateValues($object, $instance, $plugin_definition, $form_display_options) {
    $object_field = array();
    if ($referenceble = \Drupal::service('plugin.manager.entity_reference.selection')->getSelectionHandler($instance, $object)->getReferenceableEntities()) {
      $group = array_rand($referenceble);
      $object_field['target_id'] = array_rand($referenceble[$group]);
    }
    return $object_field;
  }

}
