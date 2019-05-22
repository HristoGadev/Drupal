<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/7/2019
 * Time: 11:11 AM
 */

namespace Drupal\users_register_module\Form;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;


class DeleteForm extends ConfirmFormBase
{
    public $cid;

    /**
     * {@inheritdoc}
     */
    public function getQuestion()
    {
        return t('Do you want to delete?');
    }
    public function getCancelUrl() {
        return new Url('users_register_module.content');
    }
    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return t('Cancel');
    }
    public function getDescription() {
        return t('Only do this if you are sure!');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return t('Delete it!');
    }
    public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {
        $this->id= $cid;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'delete_form';
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $connection=\Drupal::database();


        $message="Delete user";
        $connection->delete('users_registration')
            ->condition('uid',$this->id)
            ->execute();

            $this->insertTrackers($connection,$message);
            $form_state->setRedirect('users_register_module.content');

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