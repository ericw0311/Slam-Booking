<?php
// src/SD/CoreBundle/Controller/FileController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\File;
use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\FileEditContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Form\FileType;

use SD\CoreBundle\EventListener\FileEventsSubscriber;
use SD\CoreBundle\Api\AdministrationApi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FileController extends Controller {

	// Affichage des dossiers de l'utilisateur connecte (le lien entre l'utilisateur et les dossiers se fait via la table userFile)
    public function indexAction($pageNumber)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $fileRepository = $em->getRepository('SDCoreBundle:File');
    $numberRecords = $fileRepository->getUserFilesCount($connectedUser);

    $listContext = new ListContext($em, $connectedUser, 'core', 'file', $pageNumber, $numberRecords);

    $listFiles = $fileRepository->getUserDisplayedFiles($connectedUser, $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());

    return $this->render('SDCoreBundle:File:index.html.twig', array(
        'userContext' => $userContext,
        'listContext' => $listContext,
        'listFiles' => $listFiles));
    }


    // Ajout d'un dossier
    public function addAction(Request $request) 
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $file = new File($connectedUser);
    $form = $this->createForm(FileType::class, $file);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->persist($file);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'file.created.ok');

        return $this->redirectToRoute('sd_core_file_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:File:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }


    // Edition du detail d'un dossier
    /**
    * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
    */
    public function editAction(File $file) 
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $fileEditContext = new FileEditContext($em, $file); // contexte dossier

    return $this->render('SDCoreBundle:File:edit.html.twig', array('userContext' => $userContext, 'file' => $file, 'fileEditContext' => $fileEditContext));
    }


    // Modification d'un dossier
    /**
    * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
    */
    public function modifyAction(File $file, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(FileType::class, $file);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà le dossier
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'file.updated.ok');

        return $this->redirectToRoute('sd_core_file_edit', array('fileID' => $file->getId()));
    }
    return $this->render('SDCoreBundle:File:modify.html.twig', array('userContext' => $userContext, 'file' => $file, 'form' => $form->createView()));
    }


    // Suppression d'un dossier
    /**
    * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
    */
    public function deleteAction(File $file, Request $request) 
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $currentFile = ($file->getId() == $userContext->getCurrentFileID()); // On repere si le dossier a supprimer est le dossier en cours.

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà le dossier
        $em->remove($file);
        $em->flush();
        if ($currentFile) { // Si le dossier supprime etait le dossier en cours, on positionne le premier dossier de la liste comme dossier en cours
            AdministrationApi::setFirstFileAsCurrent($em, $connectedUser);
        }
        $request->getSession()->getFlashBag()->add('notice', 'file.deleted.ok');

        return $this->redirectToRoute('sd_core_file_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:File:delete.html.twig', array('userContext' => $userContext, 'file' => $file, 'form' => $form->createView()));
    }

    // Affichage des grilles horaires d'un dossier
    /**
    * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
    */
    public function foreignAction(File $file, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
	$listUserFiles = $userFileRepository->getUserFilesExceptFileCreator($file);

    $timetableRepository = $em->getRepository('SDCoreBundle:Timetable');
    $listUserTimetables = $timetableRepository->getUserTimetables($file);
                
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $listResources = $resourceRepository->getResources($file);

    $labelRepository = $em->getRepository('SDCoreBundle:Label');
    $listLabels = $labelRepository->getLabels($file);

    return $this->render('SDCoreBundle:File:foreign.html.twig', array(
		'userContext' => $userContext, 'file' => $file,
		'listUserFiles' => $listUserFiles,
		'listUserTimetables' => $listUserTimetables,
		'listResources' => $listResources,
		'listLabels' => $listLabels));
    }

    // Positionnement d'un dossier comme dossier en cours
    /**
    * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
    */
    public function set_currentAction(File $file, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    // Mise a jour du dossier en cours
    AdministrationApi::setCurrentFile($em, $connectedUser, $file);

    $userContext->setCurrentFile($file); // Mettre a jour le dossier en cours dans le contexte utilisateur

    $request->getSession()->getFlashBag()->add('notice', 'file.current.updated.ok');
    return $this->redirectToRoute('sd_core_file_edit', array('fileID' => $file->getId()));
    }
}
