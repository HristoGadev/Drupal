<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/3/2019
 * Time: 7:13 PM
 */

namespace Drupal\users_register_module\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;



class RegisterForm extends FormBase
{

    public function getFormId()
    {
        return 'register';
    }


    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $result = array();
        if (isset($_GET['num'])) {
            $query = \Drupal::database()->select('users_registration', 'm')
                ->fields('m', array('uid', 'name', 'email'))
                ->condition('uid', $_GET['num']);

            $result = $query
                ->execute()
                ->fetchAssoc();


        }

        $form['username'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Username'),
            '#default_value' => (isset($result['name']) && $_GET['num']) ? $result['name'] : '',
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#default_value' => (isset($result['email']) && $_GET['num']) ? $result['email'] : '',

        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Register'),
        ];
        return $form;
    }


    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $mail = $form_state->getValue('email');
        \Drupal::service('email.validator')->isValid($mail);
    }


    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $connection = \Drupal::database();


        $entry = [
            'name' => $form_state->getValue('username'),
            'email' => $form_state->getValue('email'),
        ];

        if (isset($_GET['num'])) {
            $field = array(
                'name' => $entry['name'],
                'email' => $entry['email'],
            );


            $connection->update('users_registration')
                ->fields($field)
                ->condition('uid', $_GET['num'])
                ->execute();
            $username=$_POST['username'];
            $email=$_POST['email'];

            $message="Edit user: $username"." with email: "."$email";
            $this->insertTrackers($connection,$message);

            $form_state->setRedirect('users_register_module.content');


        } else {
            try {
                $return_value = NULL;
                $return_value = $connection->insert('users_registration')
                    ->fields($entry)
                    ->execute();

                $username=$_POST['username'];
                $email=$_POST['email'];

                $message="Register user: $username"." with email: "."$email";
                $this->insertTrackers($connection,$message);
                $form_state->setRedirect('users_register_module.content');

            } catch (\Exception $e) {
                $this->messenger()->addMessage(t('register failed. Message = %message', [
                    '%message' => 'There is already such an email',
                ]), 'error');
                $form_state->setRedirect('users_register_module.content');
                return $return_value;
            }
            if ($return_value) {
                $this->messenger()->addMessage($this->t('User successfully created!'));
            }
        }

    }

    private function insertTrackers($query,$message)
    {
        try {
            $user = $this->currentUser();

            $rows = array(
                'uid' => $user->id(),
                'message' => $message,
                'name' => $user->getAccountName()
            );
            $query->insert('trackers')
                ->fields($rows)
                ->execute();
        } catch (\Exception $e) {
            $this->messenger()->addMessage(t('register failed. Message = %message', [
                '%message' => 'There is already such an email',
            ]), 'error');


        }

    }


}
