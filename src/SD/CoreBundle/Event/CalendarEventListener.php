<?php
// src/SD/CoreBundle/Event/CalendarEventListener.php
namespace SD\CoreBundle\Event;
use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\Constants;

use SD\CoreBundle\Api\ResourceApi;
use SD\CoreBundle\Api\PlanningApi;

class CalendarEventListener
{
    private $router;
    private $security;
    private $doctrine;
    private $logger;
    public function __construct($router, $security, $doctrine, $logger)
    {
    $this->router = $router;
    $this->security = $security;
    $this->doctrine = $doctrine;
    $this->logger = $logger;
    }
    public function getRouter()
    {
    return $this->router;
    }
    public function getSecurity()
    {
    return $this->security;
    }
    public function getDoctrine()
    {
    return $this->doctrine;
    }
    public function getLogger()
    {
    return $this->logger;
    }
    public function getUser()
    {
    return $this->getSecurity()->getToken()->getUser();
    }
    public function loadEvents(CalendarEvent $calendarEvent)
    {
	$this->getLogger()->info('CalendarEventListener.loadEvents 1');
    $em = $this->getDoctrine()->getManager();
    $connectedUser = $this->getUser();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$startDate = $calendarEvent->getStartDatetime();
	$endDate = $calendarEvent->getEndDatetime();
	// The original request so you can get filters from the calendar
	// Use the filter in your query for example
	$request = $calendarEvent->getRequest();
	$filter = $request->get('filter');
	// load events using your custom logic here,
	// for instance, retrieving events from a repository

	$this->getLogger()->info('CalendarEventListener.loadEvents 2');
	$pRepository = $em->getRepository('SDCoreBundle:Planification');
	$ppRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');
	$bRepository = $em->getRepository('SDCoreBundle:Booking');
	$buRepository = $em->getRepository('SDCoreBundle:BookingUser');

	$planificationID = PlanningApi::getCurrentCalendarPlanificationID($em, $connectedUser);
	// $currentCalendarMany = PlanningApi::getCurrentCalendarManyValue($em, $connectedUser);


	$resourcesColors = ResourceApi::getCalendarResourcesColor($em, $pRepository->find($planificationID));

	// On recherche la periode de planification a partir de la planification et de la date de début.
	$planificationPeriod = $ppRepository->getPlanificationPeriod($pRepository->find($planificationID), $startDate);
	$evenResourcesID = ResourceApi::getEvenPlanifiedResourcesID($em, $planificationPeriod);

	$this->getLogger()->info('CalendarEventListener.loadEvents 2-bis _'.$planificationID.'_ planificationPeriod _'.$planificationPeriod->getID().'_');

	$SBEvents = $bRepository->getCalendarBookings($userContext->getCurrentFile(), $pRepository->find($planificationID));

	$this->getLogger()->info('CalendarEventListener.loadEvents 3');

	$memo_date = '';
	$memo_resource_id = 0;
	$resourceBookingCount = 0;

	foreach($SBEvents as $SBEvent) {
		$bookingUser = $buRepository->findOneBy(array('booking' => $SBEvent, 'order' => 1));

		// On compte les réservations par ressource et par jour
		if ($SBEvent->getBeginningDate()->format('Y-m-d') != $memo_date || $SBEvent->getResource()->getID() != $memo_resource_id) { $resourceBookingCount = 0; }
		$resourceBookingCount++;

		// Attention, dans l'affichage par grille horaire, le compteur des réservations par ressource et par jour n'est pas correct.
		// Il est à 0 pour la première réservation, à 1 pour la deuxième, etc...
		// Ici il est correct, c'est pour cela qu'il y a une inversion des classes entre pair et impair.
		$cellClass = (in_array($SBEvent->getResource()->getID(), $evenResourcesID) ? ((($resourceBookingCount % 2) < 1) ? 'warning' : 'success') : ((($resourceBookingCount % 2) < 1) ? 'danger' : 'info'));
		$fgColor = '#000000';
		$bgColor = Constants::CALENDAR_COLOR[$cellClass];

		$this->getLogger()->info('CalendarEventListener.loadEvents 4 _'.$SBEvent->getBeginningDate()->format('Y-m-d').'_'.$SBEvent->getResource()->getID().'_'.$SBEvent->getResource()->getName().'_');

		$eventEntity = new EventEntity($bookingUser->getUserFile()->getFirstAndLastName(), $SBEvent->getBeginningDate(), $SBEvent->getEndDate());
		//optional calendar event settings
		$eventEntity->setAllDay(false); // default is false, set to true if this is an all day event

		$eventEntity->setBgColor($bgColor); //set the background color of the event's label
		$eventEntity->setFgColor($fgColor); //set the foreground color of the event's label
		$eventEntity->setUrl($this->getRouter()->generate('sd_core_planning_one_timetable',
	array('planificationID' => $SBEvent->getPlanification()->getID(), 'date' => $SBEvent->getBeginningDate()->format("Ymd")))); // url to send user to when event label is clicked

		// $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
		//finally, add the event to the CalendarEvent for displaying on the calendar
		$calendarEvent->addEvent($eventEntity);
		$memo_date = $SBEvent->getBeginningDate()->format('Y-m-d');
		$memo_resource_id = $SBEvent->getResource()->getID();
	}
    }
}
