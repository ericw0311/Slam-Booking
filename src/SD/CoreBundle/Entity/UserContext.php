<?php

namespace SD\CoreBundle\Entity;

use SD\CoreBundle\Api\AdministrationApi;

class UserContext
{
    protected $user;
    protected $currentFile;
    protected $currentUserFile;

    public function setUser($user)
    {
    $this->user = $user;
    return $this;
    }

    public function getUser()
    {
    return $this->user;
    }

    public function setCurrentFile($currentFile)
    {
    $this->currentFile = $currentFile;
    return $this;
    }

    public function getCurrentFile()
    {
    return $this->currentFile;
    }

    public function getCurrentUserFile()
    {
    return $this->currentUserFile;
    }

    public function getCurrentUserFileAdministrator()
    {
    if ($this->currentUserFile != null) {
        return $this->currentUserFile->getSDadministrator();
    } else {
        return false;
    }
    }

    function __construct($em, $user)
    {
    $this->user = $user;

    if ($user != null) { // Peut etre appele sans utilisateur connecte (home page)
        // Recherche du dossier en cours
        $currentFileID = AdministrationApi::getCurrentFileID($em, $user);

        $fileRepository = $em->getRepository('SDCoreBundle:File');
        $this->currentFile = $fileRepository->find($currentFileID);
        
        $userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
        $this->currentUserFile = $userFileRepository->findOneBy(array('account' => $this->user, 'file' => $this->currentFile));
    }
    return $this;
    }

	
    public function getCurrentFileID()
    {
    if (null === $this->currentFile) {
        return 0;
    }
    return $this->getCurrentFile()->getID();
    }

	
    // Retourne le nom du dossier en cours ou "Slam Booking" si aucun dossier en cours
    public function getCurrentFileName()
    {
    if (null === $this->currentFile) {
        return "Slam Booking";
    }
    return $this->getCurrentFile()->getName();
    }
}
