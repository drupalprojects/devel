<?php

/**
 * @file
 * Contains \Drupal\devel\Tests\DevelEntityDebugTest.
 */

namespace Drupal\devel\Tests;

use Drupal\comment\CommentInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Language\LanguageInterface;
use Drupal\devel\Controller\DevelController;
use Drupal\simpletest\WebTestBase;

/**
 * Tests DevelController entities debug functions.
 *
 * @group devel
 */
class DevelEntityDebugTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('devel', 'node', 'taxonomy', 'comment');

  /**
   * The devel controller.
   *
   * @var \Drupal\devel\Controller\DevelController
   */
  protected $develController;

  /**
   * Node being tested.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * Comment being tested.
   *
   * @var \Drupal\comment\CommentInterface
   */
  protected $comment;

  /**
   * Term being tested.
   *
   * @var \Drupal\taxonomy\TermInterface
   */
  protected $taxonomyTerm;

  /**
   * User being tested.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * Set up test.
   */
  protected function setUp() {
    parent::setUp();

    $this->develController = new DevelController();

    // Create Article node types and create comment field on it.
    if ($this->profile != 'standard') {
      $this->drupalCreateContentType(array('type' => 'article', 'name' => 'Article'));
      $this->container->get('comment.manager')->addDefaultField('node', 'article');
    }

    // Create some entities
    $this->node = $this->drupalCreateNode(array('type' => 'article'));

    $comment = entity_create('comment', array(
      'subject' => $this->randomMachineName(),
      'comment_body' => $this->randomMachineName(),
      'entity_id' => $this->node->id(),
      'entity_type' => 'node',
      'field_name' => 'comment',
      'status' => CommentInterface::PUBLISHED,
    ));
    $comment->save();

    $this->comment = $comment;

    $vocabulary = entity_create('taxonomy_vocabulary', array(
      'name' => $this->randomMachineName(),
      'description' => $this->randomMachineName(),
      'vid' => Unicode::strtolower($this->randomMachineName()),
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
      'weight' => mt_rand(0, 10),
    ));
    $vocabulary->save();

    $term = entity_create('taxonomy_term', array(
      'name' => $this->randomMachineName(),
      'description' => array(
        'value' => $this->randomMachineName(),
      ),
      'vid' => $vocabulary->id(),
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ));
    $term->save();

    $this->taxonomyTerm = $term;

    $this->user = $this->drupalCreateUser();

    $web_user = $this->drupalCreateUser(array('access devel information'));
    $this->drupalLogin($web_user);
  }

  /**
   * Test if DevelController::entityObject and DevelController::renderEntity
   * methods work properly on involved entities.
   *
   * @see \Drupal\devel\Controller\DevelController
   */
  public function testEntityDebug() {
    // @TODO uncomment after recursion's problem in _devel_print_object
    //   are solved
    //$this->entitiesDebug();
  }

  /**
   * Test if DevelController::entityObject and DevelController::renderEntity
   * methods work properly on involved entities with Kint enabled.
   *
   * @see \Drupal\devel\Controller\DevelController
   */
  public function testEntityDebugKint() {
    $installed = $this->container->get('module_installer')->install(array('kint'));
    $this->assertTrue($installed, 'Devel Kint module installed successfully');

    if (has_kint()) {
      \Kint::$displayCalledFrom = FALSE;
    }

    $this->entitiesDebug();
  }

  /**
   * Test if DevelController::entityObject and DevelController::renderEntity
   * methods work properly.
   */
  protected function entitiesDebug() {
    // Test methods related to node entities.
    $expected = kdevel_print_object($this->node, '$' . $this->node->label() . '->');
    $node_object = $this->develController->nodeLoad($this->node);
    $this->assertEqual($expected, drupal_render($node_object), 'DevelController::entityObject method works correctly with node entities');

    $expected = kdevel_print_object(node_view($this->node), '$' . $this->node->label() . '->');
    $node_render = $this->develController->nodeRender($this->node);
    $this->assertEqual($expected, drupal_render($node_render), 'DevelController::renderEntity method works correctly with node entities');

    // Test methods related to comment entities.
    $expected = kdevel_print_object($this->comment, '$' . $this->comment->label() . '->');
    $comment_object = $this->develController->commentLoad($this->comment);
    $this->assertEqual($expected, drupal_render($comment_object), 'DevelController::entityObject method works correctly with comment entities');

    $expected = kdevel_print_object(comment_view($this->comment), '$' . $this->comment->label() . '->');
    $comment_render = $this->develController->commentRender($this->comment);
    $this->assertEqual($expected, drupal_render($comment_render), 'DevelController::renderEntity method works correctly with comment entities');

    // Test methods related to taxonomy term entities.
    $expected = kdevel_print_object($this->taxonomyTerm, '$' . $this->taxonomyTerm->label() . '->');
    $taxonomy_term_object = $this->develController->taxonomyTermLoad($this->taxonomyTerm);
    $this->assertEqual($expected, drupal_render($taxonomy_term_object), 'DevelController::entityObject method works correctly with taxonomy term entities');

    $expected = kdevel_print_object(taxonomy_term_view($this->taxonomyTerm), '$' . $this->taxonomyTerm->label() . '->');
    $taxonomy_term_render = $this->develController->taxonomyTermRender($this->taxonomyTerm);
    $this->assertEqual($expected, drupal_render($taxonomy_term_render), 'DevelController::renderEntity method works correctly with taxonomy term entities');

    // Test methods related to user entities.
    $expected = kdevel_print_object($this->user, '$' . $this->user->label() . '->');
    $user_object = $this->develController->userLoad($this->user);
    $this->assertEqual($expected, drupal_render($user_object), 'DevelController::entityObject method works correctly with user entities');

    $expected = kdevel_print_object(user_view($this->user), '$' . $this->user->label() . '->');
    $user_render = $this->develController->userRender($this->user);
    $this->assertEqual($expected, drupal_render($user_render), 'DevelController::renderEntity method works correctly with user entities');

  }

}
