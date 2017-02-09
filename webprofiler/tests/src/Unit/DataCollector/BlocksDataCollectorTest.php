<?php

namespace Drupal\Tests\webprofiler\Unit\DataCollector;

use Drupal\webprofiler\DataCollector\BlocksDataCollector;
use Drupal\webprofiler\Entity\Decorators\Config\ConfigEntityStorageDecorator;
use Drupal\webprofiler\Entity\EntityManagerWrapper;
use Drupal\webprofiler\Entity\EntityViewBuilderDecorator;

/**
 * @coversDefaultClass \Drupal\webprofiler\DataCollector\BlocksDataCollector
 *
 * @group webprofiler
 */
class BlocksDataCollectorTest extends DataCollectorBaseTest {

  /**
   * @var \Drupal\webprofiler\DataCollector\BlocksDataCollector
   */
  private $blocksDataCollector;

  /**
   * @var \PHPUnit_Framework_MockObject_MockObject
   */
  private $entityTypeManager;

  /**
   * @var
   */
  private $block;

  /**
   * @var
   */
  private $entityStorage;

  /**
   * @var
   */
  private $entityManagerWrapper;

  /**
   * @var
   */
  private $configEntityStorageDecorator;

  /**
   * @var
   */
  private $entityViewBuilder;

  /**
   * @var
   */
  private $entityViewBuilderDecorator;

  /**
   * @var
   */
  private $entity;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->entityTypeManager = $this->getMockBuilder('Drupal\Core\Entity\EntityTypeManagerInterface')->getMock();
    $this->entityStorage = $this->getMockBuilder('Drupal\Core\Config\Entity\ConfigEntityStorageInterface')->getMock();
    $this->entityViewBuilder = $this->getMockBuilder('Drupal\Core\Entity\EntityViewBuilderInterface')->getMock();
    $this->entity = $this->getMockBuilder('Drupal\Core\Entity\EntityInterface')->getMock();
    $this->block = $this->getMockBuilder('Drupal\block\BlockInterface')->getMock();

    $this->entityManagerWrapper = new EntityManagerWrapper($this->entityTypeManager);
    $this->blocksDataCollector = new BlocksDataCollector($this->entityManagerWrapper);
    $this->configEntityStorageDecorator = new ConfigEntityStorageDecorator($this->entityStorage);
    $this->entityViewBuilderDecorator = new EntityViewBuilderDecorator($this->entityViewBuilder);
  }

  /**
   * Tests the Assets data collector.
   */
  public function testLoadedBlock() {
    $this->block->expects($this->once())
      ->method('getPluginId')
      ->will($this->returnValue('block_id'));

    $this->entityStorage->expects($this->atMost(2))
      ->method('load')
      ->will($this->returnValue($this->block));

    $this->entityTypeManager->expects($this->atMost(2))
      ->method('getHandler')
      ->will($this->returnValue($this->configEntityStorageDecorator));

    /** @var \Drupal\Core\Entity\EntityStorageInterface $handler * */
    $handler = $this->entityManagerWrapper->getStorage('block');
    $this->assertNotNull($handler);

    $block = $handler->load('block_id');
    $this->assertEquals('block_id', $block->getPluginId());

    $this->blocksDataCollector->collect($this->request, $this->response, $this->exception);

    $this->assertCount(1, $this->blocksDataCollector->getLoadedBlocks());
    $this->assertEquals(1, $this->blocksDataCollector->getLoadedBlocksCount());
  }

  /**
   * Tests the Assets data collector.
   */
  public function testRenderedBlock() {
    $this->block->expects($this->once())
      ->method('getPluginId')
      ->will($this->returnValue('block_id'));

    $this->entityStorage->expects($this->atMost(2))
      ->method('load')
      ->will($this->returnValue($this->block));

    $this->entityTypeManager->expects($this->atMost(2))
      ->method('getHandler')
      ->withConsecutive(
        ['block', 'view_builder'],
        ['block', 'storage']
      )
      ->willReturnOnConsecutiveCalls(
        $this->returnValue($this->entityViewBuilderDecorator),
        $this->returnValue($this->configEntityStorageDecorator)
      );

    /** @var \Drupal\Core\Entity\EntityStorageInterface $handler * */
    $handler = $this->entityManagerWrapper->getViewBuilder('block');
    $this->assertNotNull($handler);

    $block = $handler->view($this->entity);
//    $this->assertEquals('block_id', $block->getPluginId());

    $this->blocksDataCollector->collect($this->request, $this->response, $this->exception);

    $this->assertCount(1, $this->blocksDataCollector->getRenderedBlocks());
    $this->assertEquals(1, $this->blocksDataCollector->getRenderedBlocksCount());
  }

}
