<?php
// src/SD/CoreBundle/Event/SBEventSubscriber.php
namespace SD\CoreBundle\Event;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Entity\File;

class SBEventSubscriber implements EventSubscriber
{
    private $security;

    public function __construct($security)
    {
    $this->security = $security;
    }

    public function getSecurity()
    {
    return $this->security;
    }

    public function getUser()
    {
    return $this->getSecurity()->getToken()->getUser();
    }

    public function getSubscribedEvents()
    {
    return array('postPersist' /* , 'postUpdate', 'postRemove' */ );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
    $entity = $args->getObject();
    $entityManager = $args->getObjectManager();

    if ($entity instanceof File) {
        FileEvent::postPersist($entityManager, $this->getUser(), $entity);
    }

    }

    public function postUpdate(LifecycleEventArgs $args)
    {
    $entity = $args->getObject();
    $entityManager = $args->getObjectManager();

    if ($entity instanceof File) {
        $trace = new Trace();
        $trace->setMessage('FileEventsSubscriber.postUpdate 1 _'.$this->getUser()->getUsername().'_');
        $entityManager->persist($trace);
        $entityManager->flush();
        $trace = new Trace();
        $trace->setMessage('FileEventsSubscriber.postUpdate 2 _'.$entity->getID().'_'.$entity->getName().'_');
        $entityManager->persist($trace);
        $entityManager->flush();
    }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
    $entity = $args->getObject();
    $entityManager = $args->getObjectManager();

    if ($entity instanceof File) {
        $trace = new Trace();
        $trace->setMessage('FileEventsSubscriber.postRemove 1 _'.$this->getUser()->getUsername().'_');
        $entityManager->persist($trace);
        $entityManager->flush();
        $trace = new Trace();
        $trace->setMessage('FileEventsSubscriber.postRemove 2 _'.$entity->getID().'_'.$entity->getName().'_');
        $entityManager->persist($trace);
        $entityManager->flush();
    }
    }
}
