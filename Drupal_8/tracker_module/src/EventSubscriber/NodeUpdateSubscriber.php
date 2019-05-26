<?php

namespace Drupal\tracker_module\EventSubscriber;

use Drupal\Core\Entity;
use Drupal\Core\Mail\MailFormatHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\tracker_module\Event\NodeUpdateEvent;

/**
 * Logs the creation of a new node.
 */
class NodeUpdateSubscriber implements EventSubscriberInterface
{

    /**
     * Log the creation of a new node.
     *
     * @param \Drupal\tracker_module\Event\NodeUpdateEvent $event
     */
    public function onNodeUpdate(NodeUpdateEvent $event)
    {
        $entity = $event->getEntity();
        $query=\Drupal::database();
        $currentFields = $entity->toArray();
        $originalFields = $entity->original->toArray();

        $arrResultOriginal = [];
        $arrResultCurrent = [];

        foreach ($currentFields as $key => $value) {
            $currentElement = $value[0]['value'];
            $originalElement = $originalFields[$key][0]['value'];
            if ($key === 'field_tags') {
                $currentTag = $value[0]['target_id'];
                $originalTag = $originalFields[$key][0]['target_id'];
                if ($currentTag != $originalTag) {
                    array_push($arrResultCurrent, array($key => $currentTag));
                    array_push($arrResultOriginal, array($key => $originalTag));
                }
            }
            if ($currentElement != $originalElement && $currentElement != null && $originalElement != null && $key != 'revision_timestamp') {
                array_push($arrResultCurrent, array($key => $currentElement));
                array_push($arrResultOriginal, array($key => $originalElement));

            }

        }
        $user = $entity->getOwner()->getDisplayName();
        $timeChange = 0;
        $messagge = "$user has changed: ";

        $counter = 0;
        foreach ($arrResultCurrent as $result) {
            foreach ($result as $title => $value) {
                if ($title === 'field_tags') {
                    $this->selectTags($query);
                } else if ($title === 'changed') {
                    $timeChange = $value;
                } else {
                    $currentOrig = $arrResultOriginal[$counter][$title];

                   $stringOrig=MailFormatHelper::htmlToText($currentOrig, $allowed_tags = NULL);
                    $stringCurr=MailFormatHelper::htmlToText($value, $allowed_tags = NULL);

                    $currentString = " $title from $stringOrig to $stringCurr; ";
                    $messagge .= $currentString;
                }
                $counter++;
            }
        }

        $this->insertTrackers($query,$messagge);

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events[NodeUpdateEvent::DEMO_NODE_UPDATE][] = ['onNodeUpdate'];
        return $events;
    }

    private function insertTrackers($query, $message)
    {
        try {

            $rows = array(
                'message' => $message,
            );
            $query->insert('trackers_nodes')
                ->fields($rows)
                ->execute();
        } catch (\Exception $e) {
            $this->messenger()->addMessage(t('register failed. Message = %message', [
                '%message' => 'There is already such an email',
            ]), 'error');


        }

    }
    private function selectTags($query)
    {

            $query->select()

                ->execute();

    }
}