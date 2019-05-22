<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/14/2019
 * Time: 1:23 PM
 */


namespace Drupal\tracker_module\Controller;



use Drupal\Core\Controller\ControllerBase;

class TrackerController extends ControllerBase
{
    public function showActions()
    {

        $result = \Drupal::database()->select('trackers', 'n')
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
    }


}