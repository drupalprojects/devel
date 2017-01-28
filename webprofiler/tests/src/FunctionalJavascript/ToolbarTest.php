<?php

namespace Drupal\Tests\webprofiler\FunctionalJavascript;

/**
 * Tests the JavaScript functionality of webprofiler.
 *
 * @group webprofiler
 */
class ToolbarTest extends WebprofilerTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['webprofiler', 'node', 'views'];

  public function setUp() {
    parent::setUp();

    \Drupal::configFactory()->getEditable('system.site')->set('page.front', '/node')->save(TRUE);
  }

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
   * Tests the toolbar report page.
   */
  public function testToolbarReportPage() {
    $this->loginForDashboard();

    $this->drupalGet('<front>');

    $token = $this->waitForToolbar();

    $this->drupalGet('admin/reports/profiler/list');

    $assert = $this->assertSession();
    $assert->pageTextContains($token);
  }

}
