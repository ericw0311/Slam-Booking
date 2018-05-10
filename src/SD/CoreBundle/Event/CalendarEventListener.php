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

	$this->getLogger()->info('CalendarEventListener.loadEvents 2');
	$pRepository = $entityManager->getRepository('SDCoreBundle:Planification');
	$bRepository = $entityManager->getRepository('SDCoreBundle:Booking');
	$buRepository = $entityManager->getRepository('SDCoreBundle:BookingUser');

	$currentCalendarPlanificationID = PlanningApi::getCurrentCalendarPlanificationID($entityManager, $connectedUser);
	$currentCalendarMany = PlanningApi::getCurrentCalendarManyValue($entityManager, $connectedUser);


	$resourcesColors = ResourceApi::getCalendarResourcesColor($entityManager, $pRepository->find($currentCalendarPlanificationID));

	$this->getLogger()->info('CalendarEventListener.loadEvents 2-bis _'.$currentCalendarPlanificationID.'_'.count($resourcesColors).'_');

	$SBEvents = $bRepository->getCalendarBookings($userContext->getCurrentFile(), $pRepository->find($currentCalendarPlanificationID));

	$this->getLogger()->info('CalendarEventListener.loadEvents 3');
	foreach($SBEvents as $SBEvent) {
//		$this->getLogger()->info('CalendarEventListener.loadEvents 4 _'.$SBEvent['bookingID'].'_'.$SBEvent['beginningDate']->format('YmdHi').'_'.$SBEvent['endDate']->format('YmdHi').'_');
//		$this->getLogger()->info('CalendarEventListener.loadEvents 5 _'.$SBEvent['dateTest'].'_');

		$bookingUser = $buRepository->findOneBy(array('booking' => $SBEvent, 'order' => 1));

		$eventEntity = new EventEntity($bookingUser->getUserFile()->getFirstAndLastName(), $SBEvent->getBeginningDate(), $SBEvent->getEndDate());
		//optional calendar event settings
		$eventEntity->setAllDay(false); // default is false, set to true if this is an all day event

		if (isset($resourcesColors[$SBEvent->getResource()->getID()])) {
			$bgColor = Constants::CALENDAR_RESOURCE_COLOR[$resourcesColors[$SBEvent->getResource()->getID()]]['BGC'];
			$fgColor = Constants::CALENDAR_RESOURCE_COLOR[$resourcesColors[$SBEvent->getResource()->getID()]]['FGC'];
		} else {
			$bgColor = Constants::CALENDAR_RESOURCE_DEFAULT_COLOR['BGC'];
			$fgColor = Constants::CALENDAR_RESOURCE_DEFAULT_COLOR['FGC'];
		}

		$eventEntity->setBgColor($bgColor); //set the background color of the event's label
		$eventEntity->setFgColor($fgColor); //set the foreground color of the event's label
		$eventEntity->setUrl($this->getRouter()->generate('sd_core_planning_one_timetable',
	array('planificationID' => $SBEvent->getPlanification()->getID(), 'date' => $SBEvent->getBeginningDate()->format("Ymd")))); // url to send user to when event label is clicked

		// $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
		//finally, add the event to the CalendarEvent for displaying on the calendar
		$calendarEvent->addEvent($eventEntity);
	}
    }
}
