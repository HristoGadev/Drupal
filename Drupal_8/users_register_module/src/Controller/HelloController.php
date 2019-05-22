<?php
/**
 * @file
 * Contains \Drupal\hello_module\Controller\HelloController.
 */
namespace Drupal\users_register_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase
{
    public function content() {
        $connection = \Drupal::database();

        $user = $this->currentUser();
        $isLoggedIn = $user->isAuthenticated();

        if ($isLoggedIn) {
            $message = 'Loggin';
            $this->insertTrackers($connection, $message);
        }
        return array(
            '#type' => 'markup',
            '#markup' => t('Hello, please fill your username and email !'),
        );

    }
    private function insertTrackers($query, $message)
    {
        try {
            $user = $this->currentUser();
            $dateAction = date('Y-m-d H:i:s', $user->getLastAccessedTime());
            $rows = array(
                'uid' => $user->id(),
                'message' => $message,
                'name' => $user->getAccountName(),
                'date_action' => $dateAction
            );
            $query->insert('trackers')
                ->fields($rows)
                ->execute();
        } catch (\Exception $e) {
            $this->messenger()->addMessage(t('register failed. Message = %message', [
                '%message' => 'There is problem with loggin in database',
            ]), 'error');


        }

    }
}