<?php
/**
 * @file
 * Implements tests for devel_generate submodule.
 */

namespace Drupal\devel_generate\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Language\Language;

/**
 * class DevelGenerateTest
 */
class DevelGenerateTest extends WebTestBase {

  private $vocabulary;
  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('devel_generate', 'taxonomy', 'menu', 'comment');

  /*
   * The getInfo() method provides information about the test.
   * In order for the test to be run, the getInfo() method needs
   * to be implemented.
   */
  public static function getInfo() {
    return array(
      'name' => t('Devel Generate'),
      'description' => t('Tests the logic to generate data.'),
      'group' => t('Devel'),
    );
  }

  /**
   * Prepares the testing environment
   */
  function setUp() {
    parent::setUp();

    //creating vocabulary to associate taxonomy terms generated.
    $vocabulary = entity_create('taxonomy_vocabulary', array(
      'name' => $this->randomName(),
      'description' => $this->randomName(),
      'vid' => drupal_strtolower($this->randomName()),
      'langcode' => Language::LANGCODE_NOT_SPECIFIED,
      'weight' => mt_rand(0, 10),
    ));
    $vocabulary->save();
    $this->vocabulary = $vocabulary;

    // Create Basic page and Article node types.
    if ($this->profile != 'standard') {
      $this->drupalCreateContentType(array('type' => 'page', 'name' => 'Basic Page'));
    }
  }

  /**
   * Tests generate commands
   */
  public function testGenerate() {

    $user = $this->drupalCreateUser(array(
      'administer devel_generate',
    ));
    $this->drupalLogin($user);

    //Creating users.
    $edit = array(
      'num' => 4,
    );
    $this->drupalPostForm('admin/config/development/generate/user', $edit, t('Generate'));
    $this->assertText(t('4 users created.'));
    $this->assertText(t('Generate process complete.'));

    //Creating content.
    // Generate content.
    // First we create a node in order to test the Delete content checkbox.
    $this->drupalCreateNode(array());

    $edit = array(
      'num' => 4,
      'node_types[page]' => TRUE,
      'time_range' => 604800,
      'max_comments' => 3,
      'title_length' => 4,
    );
    $this->drupalPostForm('admin/config/development/generate/content', $edit, t('Generate'));
    $this->assertText(t('Finished creating 4 nodes'));
    $this->assertText(t('Generate process complete.'));

    //Creating terms.
    $edit = array(
      'vids[]' => $this->vocabulary->vid,
      'num' => 5,
      'title_length' => 12,
    );
    $this->drupalPostForm('admin/config/development/generate/term', $edit, t('Generate'));
    $this->assertText(t('Created the following new terms: '));
    $this->assertText(t('Generate process complete.'));

    //Creating vocabularies.
    $edit = array(
      'num' => 5,
      'title_length' => 12,
      'kill' => TRUE,
    );
    $this->drupalPostForm('admin/config/development/generate/vocabs', $edit, t('Generate'));
    $this->assertText(t('Created the following new vocabularies: '));
    $this->assertText(t('Generate process complete.'));

    //Creating menus.
    $edit = array(
      'num_menus' => 5,
      'num_links' => 7,
      'title_length' => 12,
      'link_types[node]' => 1,
      'link_types[front]' => 1,
      'link_types[external]' => 1,
      'max_depth' => 4,
      'max_width' => 6,
      'kill' => 1,
    );
    $this->drupalPostForm('admin/config/development/generate/menu', $edit, t('Generate'));
    $this->assertText(t('Created the following new menus: '));
    $this->assertText(t('Created 7 new menu links'));
    $this->assertText(t('Generate process complete.'));
  }

}
