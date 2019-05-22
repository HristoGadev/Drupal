<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/5/2019
 * Time: 5:20 PM
 */

namespace Drupal\users_register_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class UsersDisplay extends ControllerBase
{

    public function showUsers()
    {

        $connection = \Drupal::database();


        $result = $connection->select('users_registration', 'n')
            ->fields('n', array('uid', 'name', 'email'))
            ->execute()
            ->fetchAllAssoc('uid');


        $rows = array();
        foreach ($result as $row => $content) {

            $delete = Url::fromUserInput('/delete/' . $content->uid);
            $edit = Url::fromUserInput('/login?num=' . $content->uid);
            $deleteLink = Link::fromTextAndUrl('delete', $delete)->toString();
            $editLink = Link::fromTextAndUrl('edit', $edit)->toString();

            $rows[] = array(
                'data' => array($content->name, $content->email, $editLink, $deleteLink),

            );
        }


        $header = array('name', 'email', 'edit user', 'delete user');
        $output = array(
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows
        );
        return $output;


    }



}