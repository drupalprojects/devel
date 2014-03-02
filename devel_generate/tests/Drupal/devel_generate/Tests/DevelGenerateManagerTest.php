<?php

namespace Drupal\devel_generate\Tests;

use Drupal\devel_generate\DevelGeneratePluginManager;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Language\Language;

/**
 * Tests the DevelGenerateManager.
 *
 */

if (!defined('DRUPAL_ROOT')) {

  //Looping to find drupal root folder.
  $current_dir = dirname(__DIR__);
  while (!file_exists("$current_dir/index.php")) {
    $current_dir = dirname($current_dir);
  }

  define('DRUPAL_ROOT', $current_dir);
}

class DevelGenerateManagerTest extends UnitTestCase {

  public static function getInfo() {
    return array(
      'name' => 'DevelGenerate manager',
      'description' => 'DevelGenerate manager',
      'group' => 'DevelGenerate',
    );
  }

  /**
   * Test creating an instance of the DevelGenerateManager.
   */
  public function testCreateInstance() {
    $language = new Language(array('id' => 'en'));
    $language_manager = $this->getMock('Drupal\Core\Language\LanguageManagerInterface');
    $language_manager->expects($this->once())
      ->method('getCurrentLanguage')
      ->with(Language::TYPE_INTERFACE)
      ->will($this->returnValue($language));
    $namespaces = new \ArrayObject(array('Drupal\devel_generate_example' => realpath(dirname(__FILE__) . '/../../../modules/devel_generate_example/lib')));
    $cache_backend = $this->getMock('Drupal\Core\Cache\CacheBackendInterface');

    $module_handler = $this->getMock('Drupal\Core\Extension\ModuleHandlerInterface');
    $manager = new DevelGeneratePluginManager($namespaces, $cache_backend, $language_manager, $module_handler);

    $example_instance = $manager->createInstance('example');
    $plugin_def = $example_instance->getPluginDefinition();

    $this->assertInstanceOf('Drupal\devel_generate_example\Plugin\DevelGenerate\ExampleDevelGenerate', $example_instance);
    $this->assertArrayHasKey('url', $plugin_def);
    $this->assertTrue($plugin_def['url'] == 'example');
  }
}
