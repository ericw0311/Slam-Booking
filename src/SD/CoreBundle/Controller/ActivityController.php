<?php
// src/SD/CoreBundle/Controller/ActivityController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\Activity;
use SD\CoreBundle\Form\ActivityType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ActivityController extends Controller
{
	// Affichage des activites du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $activityRepository = $em->getRepository('SDCoreBundle:Activity');

    $numberRecords = $activityRepository->getActivitiesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'core', 'activity', $pageNumber, $numberRecords, 'sd_core_activity_list', 'sd_core_activity_add');

    $listActivities = $activityRepository->getDisplayedActivities($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Activity:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listActivities' => $listActivities));
    }

	// Ajout d'une activite
    public function addAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$activity = new Activity($connectedUser, $userContext->getCurrentFile());

	$form = $this->createForm(ActivityType::class, $activity);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($activity);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'activity.created.ok');

		return $this->redirectToRoute('sd_core_activity_list', array('pageNumber' => 1));
	}
    return $this->render('SDCoreBundle:Activity:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }

	
    // Edition du detail d'une activite
    /**
    * @ParamConverter("activity", options={"mapping": {"activityID": "id"}})
    */
    public function editAction(Activity $activity)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:Activity:edit.html.twig', array('userContext' => $userContext, 'activity' => $activity));
    }

	
    // Modification d'une activite
    /**
    * @ParamConverter("activity", options={"mapping": {"activityID": "id"}})
    */
    public function modifyAction(Activity $activity, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(ActivityType::class, $activity);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'activity.updated.ok');

        return $this->redirectToRoute('sd_core_activity_edit', array('activityID' => $activity->getId()));
    }
    return $this->render('SDCoreBundle:Activity:modify.html.twig', array('userContext' => $userContext, 'activity' => $activity, 'form' => $form->createView()));
    }


    // Suppression d'une activite
    /**
    * @ParamConverter("activity", options={"mapping": {"activityID": "id"}})
    */
    public function deleteAction(Activity $activity, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->remove($activity);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'activity.deleted.ok');

        return $this->redirectToRoute('sd_core_activity_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Activity:delete.html.twig', array('userContext' => $userContext, 'activity' => $activity, 'form' => $form->createView()));
    }
}
