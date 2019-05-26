<?php

namespace Drupal\tracker_module\Event;

use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * Wraps a node insertion demo event for event listeners.
 */
class NodeUpdateEvent extends Event {

  const DEMO_NODE_UPDATE = 'tracker_module.node.update';

  /**
   * Node entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * Constructs a node insertion demo event object.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   */
  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get the inserted entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  public function getEntity() {
    return $this->entity;
  }
}
