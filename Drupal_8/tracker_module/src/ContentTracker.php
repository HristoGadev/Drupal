<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/22/2019
 * Time: 5:37 PM
 */

namespace Drupal\tracker_module;
use Drupal\Component\Utility\DiffArray;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node;

class ContentTracker
{

    public function getContentId(){



        $connection = \Drupal::database();

        $currentNode=\Drupal::entityTypeManager()->getStorage('node')->load(2);
        $label=$currentNode->label();
        $node=$currentNode->toArray();
        array_keys(DiffArray::diffAssocRecursive($currentNode->toArray(), $node->original->toArray()));


    }


}