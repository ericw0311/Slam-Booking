<?php
// src/SD/CoreBundle/Controller/UserFileController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Form\UserFileAddType;
use SD\CoreBundle\Form\UserFileEmailType;
use SD\CoreBundle\Form\UserFileType;
use SD\CoreBundle\Form\UserFileAccountType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use SD\CoreBundle\Entity\UserFile;
use SD\UserBundle\Entity\User;
use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;

use SD\CoreBundle\Api\AdministrationApi;
use SD\CoreBundle\Api\ResourceApi;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserFileController extends Controller
{
	// Affichage des utilisateurs du dossier en cours (userFile)
    public function indexAction($pageNumber)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $userFileRepository = $em->getRepository('SDCoreBundle:UserFile');

    $numberRecords = $userFileRepository->getUserFilesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'core', 'userFile', $pageNumber, $numberRecords, 'sd_core_userFile_list', 'sd_core_userFile_email');

    $listUserFiles = $userFileRepository->getDisplayedUserFiles($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:UserFile:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
				'listUserFiles' => $listUserFiles));
    }

	// 1ere etape de l'ajout d'un utilisateur au dossier en cours (userFile): saisie de son email.
	// Si l'utilisateur (user) correspondant existe, le userFile est cree a partir de l'utilisateur trouve.
	// Si l'utilisateur (user) correspondant n'existe pas, le userFile est cree par un formulaire (2eme etape).
    public function emailAction(Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $userFile = new UserFile($connectedUser, $userContext->getCurrentFile()); // Initialisation du userFile. Les zones lastName, firstName et email sont gerees par le formulaire UserFileEmailType
    $userFile->setAdministrator(false);
    $userFile->setUserCreated(false);

    $form = $this->createForm(UserFileEmailType::class, $userFile);
    $userFound = false;

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

        $userRepository = $em->getRepository('SDUserBundle:User'); // On recherche l'utilisateur d'apres l'email saisi
        $user = $userRepository->findOneBy(array('email' => $userFile->getEmail()));

        if ($user === null) { // L'utilisateur n'existe pas, on appelle le formulaire pour creer le userFile
            return $this->redirectToRoute('sd_core_userFile_add', array('email' => $userFile->getEmail()));

        } else { // L'utilisateur existe, on cree le userFile a partir de l'utilisateur
            $userFound = true;
            $userFile->setAccount($user);
            $userFile->setAccountType($user->getAccountType());
            $userFile->setLastName($user->getLastName());
            $userFile->setFirstName($user->getFirstName());
            $userFile->setUniqueName($user->getUniqueName());
            $userFile->setUserCreated(true);
            $userFile->setUsername($user->getUsername());
            $em->persist($userFile);
            $em->flush();
            if ($userFound) { // Mise a jour du dossier en cours de l'utilisateur trouve
                AdministrationApi::setCurrentFileIfNotDefined($em, $user, $userFile->getFile());
            }
            $request->getSession()->getFlashBag()->add('notice', 'userFile.created.ok');
            return $this->redirectToRoute('sd_core_userFile_list', array('pageNumber' => 1));
        }
    }
    return $this->render('SDCoreBundle:UserFile:email.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }


// 2eme etape de l'ajout d'un utilisateur au dossier en cours (userFile): saisie de son email.
// L'utilisateur (user) correspondant a l'email saisi a l'etape 1 n'existe pas, le userFile est cree par un formulaire.
    public function addAction($email, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $userFile = new UserFile($connectedUser, $userContext->getCurrentFile());
    $userFile->setEmail($email);
    $userFile->setAdministrator	(false);
    $userFile->setUserCreated(false);

    $form = $this->createForm(UserFileAddType::class, $userFile);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->persist($userFile);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'userFile.created.ok');
        return $this->redirectToRoute('sd_core_userFile_list', array('pageNumber' => 1));
    }

    return $this->render('SDCoreBundle:UserFile:add.html.twig', array('userContext' => $userContext, 'userFile' => $userFile, 'form' => $form->createView()));
    }

    
    // Affichage du detail d'un utilisateur du dossier en cours (userFile)
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function editAction(UserFile $userFile)
    {
    $connectedUser = $this->getUser();

    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    // L'utilisateur connecte est-il le createur du dossier ?
    $connectedUserIsFileCreator = ($connectedUser === $userContext->getCurrentFile()->getUser());

    // L'utilisateur selectionne est-il le createur du dossier ?
    $selectedUserIsFileCreator = ($userFile->getUserCreated() and $userFile->getAccount() === $userContext->getCurrentFile()->getUser());
    
    return $this->render('SDCoreBundle:UserFile:edit.html.twig', array('userContext' => $userContext, 'userFile' => $userFile,
        'connectedUserIsFileCreator' => $connectedUserIsFileCreator,
        'selectedUserIsFileCreator' => $selectedUserIsFileCreator));
    }


    // Modification d'un utilisateur du dossier en cours (userFile)
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function modifyAction(UserFile $userFile, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $userFileUserCreated = $userFile->getUserCreated(); // Information sauvegardee car peut etre modifiee par la suite

    if ($userFileUserCreated) { // L'utilisateur a modifier a un compte utilisateur de cree
        $form = $this->createForm(UserFileAccountType::class, $userFile);
    } else {
        $form = $this->createForm(UserFileType::class, $userFile);
    }
    $userFound = false;

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

        if (!$userFile->getUserCreated()) { // On traite le cas tres particulier de modification de l'email
            $userRepository = $em->getRepository('SDUserBundle:User'); // On recherche l'utilisateur d'apres l'email modifie
            $user = $userRepository->findOneBy(array('email' => $userFile->getEmail()));

            if ($user != null) { // L'utilisateur existe, on met a jour le userFile a partir de l'utilisateur
                $userFound = true;
                $userFile->setAccount($user);
                $userFile->setAccountType($user->getAccountType());
                $userFile->setLastName($user->getLastName());
                $userFile->setFirstName($user->getFirstName());
                $userFile->setUniqueName($user->getUniqueName());
                $userFile->setUserCreated(true);
                $userFile->setUsername($user->getUsername());
            }
        }
        // Inutile de persister ici, Doctrine connait déjà le userFile
        $em->flush();

        if ($userFound) { // Mise a jour du dossier en cours de l'utilisateur trouve
            AdministrationApi::setCurrentFileIfNotDefined($em, $user, $userFile->getFile());
        }
        $request->getSession()->getFlashBag()->add('notice', 'userFile.updated.ok');

        return $this->redirectToRoute('sd_core_userFile_edit', array('userFileID' => $userFile->getId()));
    }

    if ($userFileUserCreated) { // L'utilisateur a modifier a un compte utilisateur de cree
        $request->getSession()->getFlashBag()->add('notice', 'userFile.update.not.allowed.3');
    }
    return $this->render('SDCoreBundle:UserFile:modify.html.twig', array('userContext' => $userContext, 'userFile' => $userFile, 'form' => $form->createView()));
    }

    
    // Suppression d'un utilisateur du dossier en cours (userFile)
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function deleteAction(UserFile $userFile, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $userAccount = $userFile->getAccount(); // Compte utilisateur attaché au userFile

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà le userFile
        $em->remove($userFile);
        $em->flush();
        
        if ($userAccount != null) { // Le userFile a un compte utilisateur attaché
            $currentFileID = AdministrationApi::getCurrentFileID($em, $userAccount);
            if ($currentFileID == $userContext->getCurrentFileID()) { // Son dossier en cours est le dossier en cours de l'utilisateur connecte
                AdministrationApi::setFirstFileAsCurrent($em, $userAccount); // On met a jour son dossier en cours
            }
        }
        $request->getSession()->getFlashBag()->add('notice', 'userFile.deleted.ok');

        return $this->redirectToRoute('sd_core_userFile_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:UserFile:delete.html.twig', array('userContext' => $userContext, 'userFile' => $userFile, 'form' => $form->createView()));
    }


    // Gestion des utilisateurs ressource
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function resourceAction(UserFile $userFile, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$resourceType = 'USER';

	// Classifications internes actives
    $activeInternalRC = ResourceApi::getActiveInternalResourceClassifications($em, $userContext->getCurrentFile(), $resourceType);

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	// Classifications externes
    $listExternalRC = $RCRepository->getExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);

	$request->getSession()->getFlashBag()->add('notice', 'userFile.update.not.allowed.3');
    return $this->render('SDCoreBundle:UserFile:resource.html.twig', 
		array('userContext' => $userContext, 'userFile' => $userFile, 'resourceType' => $resourceType,
			'activeInternalRC' => $activeInternalRC, 'listExternalRC' => $listExternalRC));
    }
}
