<?php
// src/SD/CoreBundle/Event/FileEvent.php
namespace SD\CoreBundle\Event;

use SD\CoreBundle\Entity\File;
use SD\CoreBundle\Entity\UserFile;
use SD\CoreBundle\Entity\UserParameter;

use SD\CoreBundle\Api\AdministrationApi;

class FileEvent
{

    static function postPersist($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file)
    {
        FileEvent::createUserFile($em, $user, $file);
        AdministrationApi::setCurrentFileIfNotDefined($em, $user, $file);
    }


    // Rattache l'utilisateur courant au dossier
    static function createUserFile($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file)
    {
    $userFile = new UserFile($user, $file);
    $userFile->setAccount($user);
    $userFile->setEmail($user->getEmail());
    $userFile->setAccountType($user->getAccountType());
    $userFile->setLastName($user->getLastName());
    $userFile->setFirstName($user->getFirstName());
    $userFile->setUniqueName($user->getUniqueName());
    $userFile->setAdministrator(true); // Le createur du dossier est administrateur.
    $userFile->setUserCreated(true);
    $userFile->setUsername($user->getUsername());
    $em->persist($userFile);
    $em->flush();
    }
}
