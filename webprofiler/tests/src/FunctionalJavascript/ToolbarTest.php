<?php

namespace Drupal\Tests\webprofiler\FunctionalJavascript;

/**
 * Tests the JavaScript functionality of webprofiler.
 *
 * @group toolbar
 */
class ToolbarTest extends WebprofilerTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['webprofiler', 'node'];

  /**
   * Tests if the toolbar appears on front page.
   */
  public function testToolbarOnFrontPage() {
    $this->loginForToolbar();

    $this->drupalGet('<front>');

    $this->waitForToolbar();

    $assert = $this->assertSession();
    $assert->pageTextContains(\Drupal::VERSION);
    $assert->pageTextContains('Configure Webprofiler');
    $assert->pageTextContains('View latest reports');
    $assert->pageTextContains('Drupal Documentation');
    $assert->pageTextContains('Get involved!');
  }

  /**
   * Tests if the toolbar report page.
   */
  public function testToolbarReportPage() {
    $this->loginForToolbar();

    $this->drupalGet('<front>');

    $token = $this->waitForToolbar();

    $this->drupalGet('admin/reports/profiler/list');

    $assert = $this->assertSession();
    $assert->pageTextContains($token);
  }

}
