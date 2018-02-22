<?php
// src/SD/CoreBundle/Event/CalendarEventListener.php
namespace SD\CoreBundle\Event;
use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

use SD\CoreBundle\Api\PlanningApi;
use SD\CoreBundle\Entity\UserContext;

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
    $entityManager = $this->getDoctrine()->getManager();
    $connectedUser = $this->getUser();
    $userContext = new UserContext($entityManager, $connectedUser); // contexte utilisateur
	$startDate = $calendarEvent->getStartDatetime();
	$endDate = $calendarEvent->getEndDatetime();
	// The original request so you can get filters from the calendar
	// Use the filter in your query for example
	$request = $calendarEvent->getRequest();
	$filter = $request->get('filter');
	// load events using your custom logic here,
	// for instance, retrieving events from a repository
	/*
	$SBEvents = $entityManager->getRepository('SDCoreBundle:Resource')
		->createQueryBuilder('r')
		->where('r.file = :file')->setParameter('file', $userContext->getCurrentFile())
		->getQuery()->getResult();
	*/

	/*
	$bRepository = $entityManager->getRepository('SDCoreBundle:Resource');
	$SBEvents = $bRepository->getCalendarBookings($userContext->getCurrentFile());

	foreach($SBEvents as $SBEvent) {
		// $this->getLogger()->info('CalendarEventListener.loadEvents 3 _'.date_format($SBEvent->get_tprr_createdAt(), 'Y-m-d H:i:s').'_');
		$eventEntity = new EventEntity($SBEvent->getName(), $SBEvent->get_tprr_createdAt(), $SBEvent->get_tprr_updatedAt());
		//optional calendar event settings
		$eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
		$eventEntity->setBgColor($SBEvent->getBackgroundColor()); //set the background color of the event's label
		$eventEntity->setFgColor($SBEvent->getForegroundColor()); //set the foreground color of the event's label
		$eventEntity->setUrl($this->getRouter()->generate('sd_core_resource_edit', array('resourceID' => $SBEvent->getID()))); // url to send user to when event label is clicked
		// $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
		//finally, add the event to the CalendarEvent for displaying on the calendar
		$calendarEvent->addEvent($eventEntity);
	}
	*/

	$this->getLogger()->info('CalendarEventListener.loadEvents 2');
	$bRepository = $entityManager->getRepository('SDCoreBundle:Booking');
	$pRepository = $entityManager->getRepository('SDCoreBundle:Planification');

	$currentCalendarPlanificationID = PlanningApi::getCurrentCalendarPlanificationID($entityManager, $connectedUser);

	$SBEvents = $bRepository->getCalendarBookings($userContext->getCurrentFile(), $pRepository->find($currentCalendarPlanificationID));

	$this->getLogger()->info('CalendarEventListener.loadEvents 3');
	foreach($SBEvents as $SBEvent) {
//		$this->getLogger()->info('CalendarEventListener.loadEvents 4 _'.$SBEvent['bookingID'].'_'.$SBEvent['beginningDate']->format('YmdHi').'_'.$SBEvent['endDate']->format('YmdHi').'_');
//		$this->getLogger()->info('CalendarEventListener.loadEvents 5 _'.$SBEvent['dateTest'].'_');
		$eventEntity = new EventEntity($SBEvent->getID().'toto', $SBEvent->getBeginningDate(), $SBEvent->getEndDate());
		//optional calendar event settings
		$eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
		$eventEntity->setBgColor('#1927c7'); //set the background color of the event's label
		$eventEntity->setFgColor('#e8dbb8'); //set the foreground color of the event's label
		$eventEntity->setUrl($this->getRouter()->generate('sd_core_resource_edit', array('resourceID' => $SBEvent->getID()))); // url to send user to when event label is clicked
		// $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
		//finally, add the event to the CalendarEvent for displaying on the calendar
		$calendarEvent->addEvent($eventEntity);
	}
    }
}
