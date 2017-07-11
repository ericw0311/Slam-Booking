<?php
// src/SD/CoreBundle/Controller/QueryBookingController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\QueryBooking;
use SD\CoreBundle\Form\QueryBookingType;

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

class QueryBookingController extends Controller
{
	// Affichage des tableaux de bord du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $queryBookingRepository = $em->getRepository('SDCoreBundle:QueryBooking');

    $numberRecords = $queryBookingRepository->getQueryBookingCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'core', 'queryBooking', $pageNumber, $numberRecords, 'sd_core_queryBooking_list', 'sd_core_queryBooking_add');

    $listQueryBooking = $queryBookingRepository->getDisplayedQueryBooking($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:QueryBooking:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listQueryBooking' => $listQueryBooking));
    }

	// Ajout d'un tableau de bord
    public function addAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$queryBooking = new QueryBooking($connectedUser, $userContext->getCurrentFile());

	$form = $this->createForm(QueryBookingType::class, $queryBooking);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($queryBooking);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'queryBooking.created.ok');

		return $this->redirectToRoute('sd_core_queryBooking_list', array('pageNumber' => 1));
	}
    return $this->render('SDCoreBundle:QueryBooking:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }

	
    // Edition du detail d'un tableau de bord
    /**
    * @ParamConverter("queryBooking", options={"mapping": {"queryBookingID": "id"}})
    */
    public function editAction(QueryBooking $queryBooking)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:QueryBooking:edit.html.twig', array('userContext' => $userContext, 'queryBooking' => $queryBooking));
    }

	
    // Modification d'un tableau de bord
    /**
    * @ParamConverter("queryBooking", options={"mapping": {"queryBookingID": "id"}})
    */
    public function modifyAction(QueryBooking $queryBooking, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(QueryBookingType::class, $queryBooking);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà le tableau de bord
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'queryBooking.updated.ok');

        return $this->redirectToRoute('sd_core_queryBooking_edit', array('queryBookingID' => $queryBooking->getId()));
    }
    return $this->render('SDCoreBundle:QueryBooking:modify.html.twig', array('userContext' => $userContext, 'queryBooking' => $queryBooking, 'form' => $form->createView()));
    }


    // Suppression d'un tableau de bord
    /**
    * @ParamConverter("queryBooking", options={"mapping": {"queryBookingID": "id"}})
    */
    public function deleteAction(QueryBooking $queryBooking, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà le tableau de bord
        $em->remove($queryBooking);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'queryBooking.deleted.ok');

        return $this->redirectToRoute('sd_core_queryBooking_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:QueryBooking:delete.html.twig', array('userContext' => $userContext, 'queryBooking' => $queryBooking, 'form' => $form->createView()));
    }
}
