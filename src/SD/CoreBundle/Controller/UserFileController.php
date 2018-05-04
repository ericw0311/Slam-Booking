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
use SD\CoreBundle\Entity\ResourceClassification;
use SD\CoreBundle\Entity\Resource;
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

    $listContext = new ListContext($em, $connectedUser, 'core', 'userFile', $pageNumber, $numberRecords);

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

	$atLeastOneUserClassification = false;
	$resourceType = 'USER';

	// Premiere classification interne active (N si non trouvée)
    $firstInternalResourceClassificationCode = ResourceApi::getFirstActiveInternalResourceClassification($em, $userContext->getCurrentFile(), $resourceType);

	// Il existe au moins une classification interne active
    if ($firstInternalResourceClassificationCode != "N") {
		$atLeastOneUserClassification = true;
	}

    if (!$atLeastOneUserClassification) {
		$RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');
		// Premiere classification externe active
		$firstExternalResourceClassification = $RCRepository->getFirsrActiveExternalResourceClassification($userContext->getCurrentFile(), $resourceType);
		// Il existe au moins une classification externe active
		if ($firstExternalResourceClassification !== null) {
			$atLeastOneUserClassification = true;
		}
	}

    return $this->render('SDCoreBundle:UserFile:edit.html.twig', array('userContext' => $userContext, 'userFile' => $userFile,
        'connectedUserIsFileCreator' => $connectedUserIsFileCreator,
        'selectedUserIsFileCreator' => $selectedUserIsFileCreator,
        'atLeastOneUserClassification' => $atLeastOneUserClassification));
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

	// Premiere classification interne active (N si non trouvée)
    $firstInternalResourceClassificationCode = ResourceApi::getFirstActiveInternalResourceClassification($em, $userContext->getCurrentFile(), $resourceType);

	// Il existe au moins une classification interne active
    if ($firstInternalResourceClassificationCode != "N") {
		return $this->redirectToRoute('sd_core_userFile_resource_internal', array('userFileID' => $userFile->getID(), 'resourceClassificationCode' => $firstInternalResourceClassificationCode, 'yes' => 0));
	}

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	// Premiere classification externe active
    $firstExternalResourceClassification = $RCRepository->getFirsrActiveExternalResourceClassification($userContext->getCurrentFile(), $resourceType);

	// Il existe au moins une classification externe active
	if ($firstExternalResourceClassification !== null) {
		return $this->redirectToRoute('sd_core_userFile_resource_external', array('userFileID' => $userFile->getID(), 'resourceClassificationID' => $firstExternalResourceClassification->getID(), 'yes' => 0));
    }

	// Cas ou aucune classification active. Normalement ce cas ne se produit pas (car dans ce cas on ne donne pas accès à la fonctionnalité utilisateur ressource)
	return $this->redirectToRoute('sd_core_userFile_resource_internal', array('userFileID' => $userFile->getID(), 'resourceClassificationCode' => $firstInternalResourceClassificationCode, 'yes' => 0));
    }


    // Gestion des utilisateurs ressource: sélection d'une classification interne
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function resource_internalAction(UserFile $userFile, $resourceClassificationCode, $yes, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$resourceType = 'USER';
	$resourceClassificationID = 0;

	// Classifications internes actives
    $listActiveInternalRC = ResourceApi::getActiveInternalResourceClassifications($em, $userContext->getCurrentFile(), $resourceType);

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	// Classifications externes actives
    $listExternalRC = $RCRepository->getActiveExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);

	$yesOrNo = ($yes > 0) ? 'yes' :'no';

    return $this->render('SDCoreBundle:UserFile:resource.'.$yesOrNo.'.html.twig',
		array('userContext' => $userContext, 'userFile' => $userFile, 'resourceType' => $resourceType, 'yes' => $yes, 'internal' => 1,
			'resourceClassificationCode' => $resourceClassificationCode, 'listActiveInternalRC' => $listActiveInternalRC,
			'resourceClassificationID' => $resourceClassificationID, 'listExternalRC' => $listExternalRC));
    }


    // Gestion des utilisateurs ressource: sélection d'une classification externe
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function resource_externalAction(UserFile $userFile, ResourceClassification $resourceClassification, $yes, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$resourceType = 'USER';
	$resourceClassificationCode = 'N';

	// Classifications internes actives
    $listActiveInternalRC = ResourceApi::getActiveInternalResourceClassifications($em, $userContext->getCurrentFile(), $resourceType);

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	// Classifications externes
    $listExternalRC = $RCRepository->getActiveExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);

	$yesOrNo = ($yes > 0) ? 'yes' :'no';

    return $this->render('SDCoreBundle:UserFile:resource.'.$yesOrNo.'.html.twig',
		array('userContext' => $userContext, 'userFile' => $userFile, 'resourceType' => $resourceType, 'yes' => $yes, 'internal' => 0,
			'resourceClassificationCode' => $resourceClassificationCode, 'listActiveInternalRC' => $listActiveInternalRC,
			'resourceClassificationID' => $resourceClassification->getID(), 'listExternalRC' => $listExternalRC));
    }


    // Gestion des utilisateurs ressource: validation d'une classification interne
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function resource_validate_internalAction_sav(UserFile $userFile, $resourceClassificationCode, $yes, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$resourceType = 'USER';

	if ($yes > 0) {
		$userFile->setResourceUser(1);
	} else {
		$userFile->setResourceUser(0);
	}
	
	if ($userFile->getResourceUser() > 0) { // Création ou mise à jour de la ressource rattachée à l'utilisateur

		$resourceUpdated = false;

		if ($userFile->getResource() !== null) {
			$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
			$resource = $resourceRepository->findOneBy(array('id' => $userFile->getResource()));
			if ($resource !== null) {
				$resource->setInternal(1);
				$resource->setCode($resourceClassificationCode);
				$resource->setClassification(null);
				$resourceUpdated = true;
			}
		}

		if (!$resourceUpdated) {
			$resource = new Resource($connectedUser, $userContext->getCurrentFile());
			$resource->setInternal(1);
			$resource->setType($resourceType);
			$resource->setCode($resourceClassificationCode);
			$resource->setBackgroundColor("#0000ff");
			$resource->setForegroundColor("#ffffff");
			$resource->setName($userFile->getFirstAndLastName());
			$em->persist($resource);

			$userFile->setResource($resource);
		}
	}

	$em->persist($userFile);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'userFile.resource.updated.ok');
	return $this->redirectToRoute('sd_core_userFile_edit', array('userFileID' => $userFile->getID()));
    }


    // Gestion des utilisateurs ressource: validation d'une classification interne
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function resource_validate_internalAction(UserFile $userFile, $resourceClassificationCode, $yes, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$resourceType = 'USER';

	$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$resourceFound = false;

	if ($userFile->getResource() !== null) {
		$resource = $resourceRepository->findOneBy(array('id' => $userFile->getResource()));
		if ($resource !== null) {
			$resourceFound = true;
		}
	}

	if ($yes > 0) {
		$userFile->setResourceUser(1);
	} else {
		$userFile->setResourceUser(0);
		$userFile->setResource(null);
	}
	
	if ($userFile->getResourceUser() > 0) { // Création ou mise à jour de la ressource rattachée à l'utilisateur

		if ($resourceFound) {
			$resource->setInternal(1);
			$resource->setCode($resourceClassificationCode);
			$resource->setClassification(null);

		} else {
			$resource = new Resource($connectedUser, $userContext->getCurrentFile());
			$resource->setInternal(1);
			$resource->setType($resourceType);
			$resource->setCode($resourceClassificationCode);
			$resource->setBackgroundColor("#0000ff");
			$resource->setForegroundColor("#ffffff");
			$resource->setName($userFile->getFirstAndLastName());
			$em->persist($resource);
			$userFile->setResource($resource);
		}
	} else {
		if ($resourceFound) {
			$em->remove($resource);
		}
	}

	$em->persist($userFile);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'userFile.resource.updated.ok');
	return $this->redirectToRoute('sd_core_userFile_edit', array('userFileID' => $userFile->getID()));
    }


    // Gestion des utilisateurs ressource: validation d'une classification externe
    /**
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function resource_validate_externalAction(UserFile $userFile, ResourceClassification $resourceClassification, $yes, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$resourceType = 'USER';

	$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$resourceFound = false;

	if ($userFile->getResource() !== null) {
		$resource = $resourceRepository->findOneBy(array('id' => $userFile->getResource()));
		if ($resource !== null) {
			$resourceFound = true;
		}
	}

	if ($yes > 0) {
		$userFile->setResourceUser(1);
	} else {
		$userFile->setResourceUser(0);
		$userFile->setResource(null);
	}
	
	if ($userFile->getResourceUser() > 0) { // Création ou mise à jour de la ressource rattachée à l'utilisateur

		if ($resourceFound) {
			$resource->setInternal(0);
			$resource->setClassification($resourceClassification);
			$resource->setCode(null);

		} else {
			$resource = new Resource($connectedUser, $userContext->getCurrentFile());
			$resource->setInternal(0);
			$resource->setType($resourceType);
			$resource->setClassification($resourceClassification);
			$resource->setBackgroundColor("#0000ff");
			$resource->setForegroundColor("#ffffff");
			$resource->setName($userFile->getFirstAndLastName());
			$em->persist($resource);
			$userFile->setResource($resource);
		}
	} else { // Suppression de la ressource rattachée à l'utilisateur
		if ($resourceFound) {
			$em->remove($resource);
		}
	}

	$em->persist($userFile);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'userFile.resource.updated.ok');
	return $this->redirectToRoute('sd_core_userFile_edit', array('userFileID' => $userFile->getID()));
    }
}
