<?php
// src/SD/CoreBundle/Controller/BookingController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\Resource;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BookingController extends Controller
{
    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $cells)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:Booking:create.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
			'resource' => $resource, 'cells' => $cells, 'date' => $date));
    }
}
