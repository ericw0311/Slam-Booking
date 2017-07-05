<?php
// src/SD/CoreBundle/Controller/TimetableController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Entity\Constants;

use SD\CoreBundle\Entity\TimetableHeader;
use SD\CoreBundle\Entity\TimetableLine;

use SD\CoreBundle\Form\TimetableHeaderType;
use SD\CoreBundle\Form\TimetableLineType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TimetableController extends Controller
{
    // Affichage des grilles horaires du dossier en cours
    public function indexAction($pageNumber)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $timetableHeaderRepository = $em->getRepository('SDCoreBundle:TimetableHeader');

    $numberRecords = $timetableHeaderRepository->getTimetablesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'core', 'timetable', $pageNumber, $numberRecords, 'sd_core_timetable_display', 'sd_core_timetable_add');

    $listTimetables = $timetableHeaderRepository->getDisplayedTimetables($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Timetable:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listTimetables' => $listTimetables));
    }

    // Ajout d'une grille horaire
    public function addAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$timetableHeader = new TimetableHeader($connectedUser, $userContext->getCurrentFile());

	$form = $this->createForm(TimetableHeaderType::class, $timetableHeader);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	$em->persist($timetableHeader);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'timetable.created.ok');

	return $this->redirectToRoute('sd_core_timetable_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Timetable:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }
	
    // Edition du detail d'une grille horaire
    /**
    * @ParamConverter("timetableHeader", options={"mapping": {"timetableHeaderID": "id"}})
    */
    public function editAction(TimetableHeader $timetableHeader)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $listTimetableLines = $timetableLineRepository->getTimetableLines($timetableHeader);

    return $this->render('SDCoreBundle:Timetable:edit.html.twig', 
        array('userContext' => $userContext, 'timetableHeader' => $timetableHeader, 'listTimetableLines' => $listTimetableLines));
    }

	
// Modification d'une grille horaire
/**
* @ParamConverter("timetableHeader", options={"mapping": {"timetableHeaderID": "id"}})
*/
public function modifyAction(TimetableHeader $timetableHeader, Request $request)
{
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(TimetableHeaderType::class, $timetableHeader);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la grille horaire
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'timetable.updated.ok');

        return $this->redirectToRoute('sd_core_timetable_edit', array('timetableHeaderID' => $timetableHeader->getId()));
    }
    return $this->render('SDCoreBundle:Timetable:modify.html.twig', array('userContext' => $userContext, 'timetableHeader' => $timetableHeader, 'form' => $form->createView()));
}


    // Suppression d'une grille horaire
    /**
    * @ParamConverter("timetableHeader", options={"mapping": {"timetableHeaderID": "id"}})
    */
    public function deleteAction(TimetableHeader $timetableHeader, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la grille horaire
        $em->remove($timetableHeader);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'timetable.deleted.ok');

        return $this->redirectToRoute('sd_core_timetable_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Timetable:delete.html.twig', array('userContext' => $userContext, 'timetableHeader' => $timetableHeader, 'form' => $form->createView()));
    }
    
    
// Ajout d'un creneau horaire
/**
* @ParamConverter("timetableHeader", options={"mapping": {"timetableHeaderID": "id"}})
*/
public function addlineAction(TimetableHeader $timetableHeader, Request $request)
{
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $listLastTimetableLines = $timetableLineRepository->getLastTimetableLines($timetableHeader, Constants::NUMBER_LINES_BEFORE_AFTER_UPDATE);

    $timetableLine = new TimetableLine($connectedUser, $timetableHeader);

    if (count($listLastTimetableLines) > 0) { // On initialise la date de début avec la date de fin du dernier créneau
		$timetableLine->setBeginningTime(current($listLastTimetableLines)->getEndTime());
	}

    $form = $this->createForm(TimetableLineType::class, $timetableLine);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->persist($timetableLine);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'timetableLine.created.ok');
		
		if ($form->get('validateAndCreate')->isClicked()) {
			return $this->redirectToRoute('sd_core_timetable_addline', array('timetableHeaderID' => $timetableHeader->getID()));
		} else {
			return $this->redirectToRoute('sd_core_timetable_edit', array('timetableHeaderID' => $timetableHeader->getID()));
		}
	}

    return $this->render('SDCoreBundle:Timetable:addline.html.twig',
        array('userContext' => $userContext, 'timetableHeader' => $timetableHeader, 'listLastTimetableLines' => $listLastTimetableLines, 'form' => $form->createView()));
}


    // Modification d'un creneau horaire
    /**
    * @ParamConverter("timetableHeader", options={"mapping": {"timetableHeaderID": "id"}})
    * @ParamConverter("timetableLine", options={"mapping": {"timetableLineID": "id"}})
    */
    public function modifylineAction(TimetableHeader $timetableHeader, TimetableLine $timetableLine, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $listPreviousTimetableLines = $timetableLineRepository->getSomeTimetableLines($timetableHeader, $timetableLine->getId(), Constants::NUMBER_LINES_BEFORE_AFTER_UPDATE, true);
    $listNextTimetableLines = $timetableLineRepository->getSomeTimetableLines($timetableHeader, $timetableLine->getId(), Constants::NUMBER_LINES_BEFORE_AFTER_UPDATE, false);
    
    $form = $this->createForm(TimetableLineType::class, $timetableLine);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà le creneau horaire
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'timetableLine.updated.ok');

        return $this->redirectToRoute('sd_core_timetable_edit', array('timetableHeaderID' => $timetableHeader->getId()));
    }

    return $this->render('SDCoreBundle:Timetable:modifyline.html.twig',
        array('userContext' => $userContext, 'timetableHeader' => $timetableHeader,
            'timetableLine' => $timetableLine,
            'listPreviousTimetableLines' => $listPreviousTimetableLines,
            'listNextTimetableLines' => $listNextTimetableLines,
            'form' => $form->createView()));
    }


    // Suppression d'un creneau horaire
    /**
    * @ParamConverter("timetableLine", options={"mapping": {"timetableLineID": "id"}})
    */
    public function deletelineAction($timetableHeaderID, TimetableLine $timetableLine, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    // Inutile de persister ici, Doctrine connait déjà la grille horaire
    $em->remove($timetableLine);
    $em->flush();
    $request->getSession()->getFlashBag()->add('notice', 'timetableLine.deleted.ok');

    return $this->redirectToRoute('sd_core_timetable_edit', array('timetableHeaderID' => $timetableHeaderID));
    }
}
