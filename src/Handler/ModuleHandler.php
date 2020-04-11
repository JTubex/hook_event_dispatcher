<?php

namespace Drupal\hook_event_dispatcher\Handler;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandler as CoreModuleHandler;
use Drupal\hook_event_dispatcher\Manager\HookEventDispatcherManagerInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ModuleHandler.
 */
final class ModuleHandler extends CoreModuleHandler {

  private const NAME = 'hook_event_dispatcher';

  /**
   * DispatcherManager.
   *
   * @var \Drupal\hook_event_dispatcher\Manager\HookEventDispatcherManagerInterface
   */
  private $dispatcherManager;

  /**
   * This is true when only the parent implementation should be used.
   *
   * @var bool
   */
  private $parentImplementation = FALSE;

  /**
   * ModuleHandler constructor.
   *
   * @param string $root
   *   App root.
   * @param array $moduleList
   *   ModuleList.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cacheBackend
   *   CacheBackend.
   * @param \Drupal\hook_event_dispatcher\Manager\HookEventDispatcherManagerInterface $dispatcherManager
   *   DispatcherManager.
   *
   * @see \Drupal\Core\Extension\ModuleHandler
   */
  public function __construct(
    string $root,
    array $moduleList,
    CacheBackendInterface $cacheBackend,
    HookEventDispatcherManagerInterface $dispatcherManager
  ) {
    parent::__construct($root, $moduleList, $cacheBackend);
    $this->dispatcherManager = $dispatcherManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getImplementations($hook): array {
    $info = parent::getImplementations($hook);

    if ($this->parentImplementation) {
      return $info;
    }

    // @TODO Should check supported?
    $info[self::NAME] = FALSE;

    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function invoke($module, $hook, array $args = []) {
    if ($module !== self::NAME) {
      return parent::invoke($module, $hook, $args);
    }

    // @TODO: MapEventFactory.
    $event = new Event();
    $this->dispatcherManager->register($event);
    return $event->getResult();
  }

  /**
   * {@inheritdoc}
   */
  public function invokeAll($hook, array $args = []): array {
    // @TODO Include this into the event dispatch.
    $this->parentImplementation = TRUE;
    $result = parent::invokeAll($hook, $args);
    $this->parentImplementation = FALSE;
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL): void {
    // @TODO Include this into the event dispatch.
    $this->parentImplementation = TRUE;
    parent::alter($type, $data, $context1, $context2);
    $this->parentImplementation = FALSE;
  }

}
