<?php

namespace Drupal\Tests\hook_event_dispatcher\Unit\Handler;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\hook_event_dispatcher\Handler\ModuleHandler;
use Drupal\Tests\UnitTestCase;

/**
 * Class ModuleHandlerTest.
 *
 * @group hook_event_dispatcher
 */
final class ModuleHandlerTest extends UnitTestCase {

  /**
   * ModuleHandler.
   *
   * @var \Drupal\hook_event_dispatcher\Handler\ModuleHandler
   */
  private $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    $cacheBackend = $this->createMock(CacheBackendInterface::class);
    $this->moduleHandler = new ModuleHandler('', [], $cacheBackend);
  }

}
