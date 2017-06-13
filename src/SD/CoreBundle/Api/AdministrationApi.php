<?php
// src/SD/CoreBundle/Api/AdministrationApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\File;
use SD\CoreBundle\Entity\UserParameter;

class AdministrationApi
{
    // Retourne le dossier en cours d'un utilisateur
    static function getCurrentFile($em, \SD\UserBundle\Entity\User $user)
    {
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');
    return $userParameterRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'booking', 'parameter' => 'current.file'));
    }


    // Retourne l'ID du dossier en cours d'un utilisateur
    static function getCurrentFileID($em, \SD\UserBundle\Entity\User $user)
    {
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');
    $userParameter = $userParameterRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'booking', 'parameter' => 'current.file'));
    if ($userParameter === null) {
        return 0;
    } else {
        return $userParameter->getIntegerValue();
    }
    }

    // Positionne le dossier comme dossier en cours
    static function setCurrentFile($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file)
    {
    // Recherche du dossier en cours
    $userParameter = AdministrationApi::getCurrentFile($em, $user);

    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'booking', 'current.file');
        $userParameter->setSDIntegerValue($file->getId());
        $em->persist($userParameter);
	} else {
        $userParameter->setSDIntegerValue($file->getId());
	}
	$em->flush();
    }


    // Positionne le dossier comme dossier en cours (idem setCurrentFile mais directement à partir de l'ID du dossier)
    static function setCurrentFileID($em, \SD\UserBundle\Entity\User $user, $fileID)
    {
    // Recherche du dossier en cours
    $userParameter = AdministrationApi::getCurrentFile($em, $user);
    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'booking', 'current.file');
        $userParameter->setSDIntegerValue($fileID);
        $em->persist($userParameter);
    } else {
        $userParameter->setSDIntegerValue($fileID);
    }
    $em->flush();
    }


    // Positionne le dossier comme dossier en cours si l'utilisateur n'a pas de dossier en cours
    static function setCurrentFileIfNotDefined($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file)
    {
    // Recherche du dossier en cours
    $userParameter = AdministrationApi::getCurrentFile($em, $user);

    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'booking', 'current.file');
        $userParameter->setSDIntegerValue($file->getId());
        $em->persist($userParameter);
        $em->flush();
    }
    }


    // Positionne le premier dossier comme dossier en cours
    static function setFirstFileAsCurrent($em, \SD\UserBundle\Entity\User $user)
    {
    // Recherche du dossier en cours
    $userParameter = AdministrationApi::getCurrentFile($em, $user);

    // Recherche du premier dossier de l'utilisateur
    $fileRepository = $em->getRepository('SDCoreBundle:File');
    $firstFile = $fileRepository->getUserFirstFile($user);
    
    $doFlush = false;

    if ($firstFile != null) { // Le premier dossier est trouve
        if ($userParameter === null) { // Mise a jour du parametre "dossier en cours"
            $userParameter = new UserParameter($user, 'booking', 'current.file');
            $userParameter->setSDIntegerValue($firstFile->getId());
            $em->persist($userParameter);
        } else { // Creation "dossier en cours"
            $userParameter->setSDIntegerValue($firstFile->getId());
        }
        $doFlush = true;
    } else { // Plus de dossier: suppression du parametre
        if ($userParameter != null) {
            $em->remove($userParameter);
            $doFlush = true;
        }
    }

    if ($doFlush) {
        $em->flush();
    }
    }
}
