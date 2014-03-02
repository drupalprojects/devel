<?php

namespace Drupal\devel_generate;

class DevelGenerateFieldTaxonomy extends DevelGenerateFieldBase {

  public function generateValues($object, $instance, $plugin_definition, $form_display_options) {
    $object_field = array();
    $settings = $instance->getFieldSettings();
    // TODO: For free tagging vocabularies that do not already have terms, this
    // will not result in any tags being added.
    $machine_name = $settings['allowed_values'][0]['vocabulary'];
    $vocabulary = entity_load('taxonomy_vocabulary', $machine_name);
    if ($max = db_query('SELECT MAX(tid) FROM {taxonomy_term_data} WHERE vid = :vid', array(':vid' => $vocabulary->vid))->fetchField()) {
      $candidate = mt_rand(1, $max);
      $query = db_select('taxonomy_term_data', 't');
      $tid = $query
        ->fields('t', array('tid'))
        ->condition('t.vid', $vocabulary->vid, '=')
        ->condition('t.tid', $candidate, '>=')
        ->range(0,1)
        ->execute()
        ->fetchField();
      // If there are no terms for the taxonomy, the query will fail, in which
      // case we return NULL.
      if ($tid === FALSE) {
        return NULL;
      }
      $object_field['target_id'] = (int) $tid;
      return $object_field;
    }
  }

}
