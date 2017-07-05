<?php

namespace Drupal\Tests\webprofiler\Functional;

use Drupal\Tests\webprofiler\FunctionalJavascript\WebprofilerTestBase;

/**
 * Tests the file storage backend option.
 *
 * @group webprofiler
 */
class FileStorageTest extends WebprofilerTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['devel', 'webprofiler'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->config('webprofiler.config')
      ->set('storage', 'profiler.file_storage')
      ->save(TRUE);

    $this->drupalLogin($this->drupalCreateUser(['access webprofiler']));
  }

  /**
   * Tests the purge on cache clear enabled setting.
   */
  public function testCacheClearEnabledOnFileStorage() {
    $this->enableCacheClear();

    $profiles = $this->getProfiles();
    $this->assertCount(4, $profiles);

    drupal_flush_all_caches();

    $profiles = $this->getProfiles();
    $this->assertCount(0, $profiles);
  }

  /**
   * Tests the purge on cache clear disabled setting.
   */
  public function testCacheClearDisabledOnFileStorage() {
    $this->disableCacheClear();

    $profiles = $this->getProfiles();
    $this->assertCount(4, $profiles);

    drupal_flush_all_caches();

    $profiles = $this->getProfiles();
    $this->assertCount(4, $profiles);
  }

  /**
   * Enables the "purge on cache clear" settings.
   */
  private function enableCacheClear() {
    $this->config('webprofiler.config')
      ->set('purge_on_cache_clear', TRUE)
      ->save(TRUE);
  }

  /**
   * Disables the "purge on cache clear" settings.
   */
  private function disableCacheClear() {
    $this->config('webprofiler.config')
      ->set('purge_on_cache_clear', FALSE)
      ->save(TRUE);
  }

  /**
   * Returns the stored profiles.
   *
   * @return array
   *   The stored profiles.
   */
  private function getProfiles() {
    /** @var \Drupal\webprofiler\Profiler\Profiler $profiler */
    $profiler = $this->container->get('profiler');

    $profiles = $profiler->find(NULL, NULL, 100, NULL, '', '');

    return $profiles;
  }

}
