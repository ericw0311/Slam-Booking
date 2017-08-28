<?php
// src/SD/CoreBundle/Event/FileEvent.php
namespace SD\CoreBundle\Event;

use SD\CoreBundle\Entity\File;
use SD\CoreBundle\Entity\UserFile;
use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\Timetable;
use SD\CoreBundle\Entity\TimetableLine;

use SD\CoreBundle\Api\AdministrationApi;

class FileEvent
{

    static function postPersist($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file, $translator)
    {
        FileEvent::createUserFile($em, $user, $file);
        AdministrationApi::setCurrentFileIfNotDefined($em, $user, $file);
        FileEvent::createTimetables($em, $user, $file, $translator);
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

    // Crée les grilles horaires D = journée et HD = demi journée
    static function createTimetables($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file, $translator)
    {
	$timetable = new Timetable($user, $file);
	$timetable->setType("D");
	$timetable->setName($translator->trans('timetable.day'));
    $em->persist($timetable);
    
	$timetableLine = new TimetableLine($user, $timetable);
	$timetableLine->setType("D");
	$timetableLine->setBeginningTime(date_create_from_format('H:i:s','00:00:00'));
	$timetableLine->setEndTime(date_create_from_format('H:i:s','23:59:00'));
    $em->persist($timetableLine);

	$timetable = new Timetable($user, $file);
	$timetable->setType("HD");
	$timetable->setName($translator->trans('timetable.half.day'));
    $em->persist($timetable);
    
	$timetableLine = new TimetableLine($user, $timetable);
	$timetableLine->setType("AM");
	$timetableLine->setBeginningTime(date_create_from_format('H:i:s','00:00:00'));
	$timetableLine->setEndTime(date_create_from_format('H:i:s','12:00:00'));
    $em->persist($timetableLine);

	$timetableLine = new TimetableLine($user, $timetable);
	$timetableLine->setType("PM");
	$timetableLine->setBeginningTime(date_create_from_format('H:i:s','12:00:00'));
	$timetableLine->setEndTime(date_create_from_format('H:i:s','23:59:00'));
    $em->persist($timetableLine);
    $em->flush();
    }
}
