<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/20/2019
 * Time: 2:21 PM
 */

namespace Drupal\tracker_module\Controller;


use Drupal\Component\Utility\DiffArray;
use Drupal\Core\Controller\ControllerBase;



use \Drupal\node\Entity\Node;

class TrackerNodeController extends ControllerBase
{

    public function trackNodes()
    {


        $currentNode=\Drupal::entityTypeManager()->getStorage('node')->load(2);
        $array=array_keys(DiffArray::diffAssocRecursive($currentNode->toArray(), $currentNode->original->toArray()));






        $result = \Drupal::database()->select('trackers_nodes', 'n')
            ->fields('n', array('name', 'message', 'date_action'))
            ->execute()
            ->fetchAll();

        $rows = array();
        foreach ($result as $row => $content) {

            $rows[] = array(
                'data' => array($content->name, $content->message, $content->date_action),
            );
        }

        $header = array('name', 'action', 'date');
        $output = array(
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows
        );
        return $output;



        /**$this->getTitleChanges($currentId);
            $this->getBodyChanges($currentId);*/

    }

    private function getTitleChanges($currentId)
    {
        $connection = \Drupal::database();
        $query = $connection->select('node_field_revision', 'nfr');
        $query->join('users_field_data', 'ufd', 'ufd.uid=nfr.uid');
        $result = $query->fields('nfr', array('title', 'changed'))
            ->fields('ufd', array('name'))
            ->condition('nfr.nid', $currentId);
        $titleResult = $result->execute()->fetchAll();

        if (count($titleResult) > 1) {
            $lastElement = end($titleResult)->title;
            $beforeLast = $titleResult[count($titleResult) - 2]->title;
            $lastUser = end($titleResult)->name;
            $lastTime = date('m/d/Y H:i:s', end($titleResult)->changed);


            if ($lastElement !== $beforeLast) {
                $titleString = "The title $beforeLast was changed to $lastElement by $lastUser at $lastTime";
                return $titleString;
            }
        }
    }

    private function getBodyChanges($currentId)
    {
        $connection = \Drupal::database();


        $query = $connection->select('node_revision', 'nr');
        $query->join('node_revision__body', 'nrb', 'nrb.revision_id=nr.vid');
        $query->join('users_field_data', 'ufd', 'nr.revision_uid=ufd.uid');
        $result = $query->fields('nfr', array('title', 'changed'))
            ->fields('ufd', array('name'))
            ->condition('nfr.nid', $currentId);
        $titleResult = $result->execute()->fetchAll();

        if (count($titleResult) > 1) {
            $lastElement = end($titleResult)->title;
            $beforeLast = $titleResult[count($titleResult) - 2]->title;
            $lastUser = end($titleResult)->name;
            $lastTime = date('m/d/Y H:i:s', end($titleResult)->changed);


            if ($lastElement !== $beforeLast) {
                $titleString = "The title $beforeLast was changed to $lastElement by $lastUser at $lastTime";
                return $titleString;
            }
        }
    }


}

