<?php
// src/UserBundle/Event/UserListener.php

namespace SD\UserBundle\Event;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Entity\UserFile;

use SD\CoreBundle\Api\AdministrationApi;

/**
 * Listener responsible to change the redirection when a form is successfully filled
 */
class UserListener implements EventSubscriberInterface
{
    private $security;
    private $doctrine;
    private $logger;
    
    public function __construct($security, $doctrine, $logger)
    {
    $this->security = $security;
    $this->doctrine = $doctrine;
    $this->logger = $logger;
    }

    public function getSecurity()
    {
    return $this->security;
    }

    public function getDoctrine()
    {
    return $this->doctrine;
    }

    public function getLogger()
    {
    return $this->logger;
    }

    public function getUser()
    {
    return $this->getSecurity()->getToken()->getUser();
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => array('onRegistrationSuccess',-10), 
            FOSUserEvents::PROFILE_EDIT_SUCCESS => array('onProfileEditSuccess',-10)
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
    $this->getLogger()->info('onRegistrationSuccess 1');
        
    $createdUser = $event->getForm()->getData();
    $entityManager = $this->getDoctrine()->getEntityManager();
        
    $atLeastOneFile = UserListener::updateUserFileFromEmail($entityManager, $createdUser);
    $this->getLogger()->info('onRegistrationSuccess 2 atLeastOneFile _'.$atLeastOneFile.'_');
    if ($atLeastOneFile) { // Si l'utilisateur enregistre est rattache a un dossier ou plus, on lui positionne un dossier en cours
        AdministrationApi::setFirstFileAsCurrent($entityManager, $createdUser);
    }
    }
    
    
    public function onProfileEditSuccess(FormEvent $event)
    {
    $updatedUser = $event->getForm()->getData();
    $entityManager = $this->getDoctrine()->getEntityManager();
        
    UserListener::updateUserFileFromAccount($entityManager, $updatedUser);
    }


    // Met a jour les utilisateurs dossiers correspondants a l'utilisateur inscrit
    // Retourne Vrai si au moins un dossier trouve
    static function updateUserFileFromEmail($em, $createdUser)
    {
    $userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
    $listUserFile = $userFileRepository->findBy(array('email' => $createdUser->getEmail()));
    $atLeastOneFile = false;

    foreach ($listUserFile as $userFile) {
        $atLeastOneFile = true;
        $userFile->setUserCreated(true);
        $userFile->setAccount($createdUser);
        $userFile->setAccountType($createdUser->getAccountType());
        $userFile->setLastName($createdUser->getLastName());
        $userFile->setFirstName($createdUser->getFirstName());
        $userFile->setUniqueName($createdUser->getUniqueName());
        $userFile->setUsername($createdUser->getUsername());
        $em->persist($userFile);
    }
    $em->flush();
    return $atLeastOneFile;
    }
  
    
    // Met a jour les utilisateurs dossiers correspondants a l'utilisateur modifie
    static function updateUserFileFromAccount($em, $updatedUser)
    {
    $userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
    $listUserFile = $userFileRepository->findBy(array('account' => $updatedUser));

    foreach ($listUserFile as $userFile) {
        $userFile->setUserCreated(true);
        $userFile->setAccountType($updatedUser->getAccountType());
        $userFile->setLastName($updatedUser->getLastName());
        $userFile->setFirstName($updatedUser->getFirstName());
        $userFile->setUniqueName($updatedUser->getUniqueName());
        $userFile->setUsername($updatedUser->getUsername());
        $userFile->setEmail($updatedUser->getEmail());
        $em->persist($userFile);
    }
    $em->flush();
    }
 }
