<?php
// src/SD/CoreBundle/Event/CalendarEventListener.php
namespace SD\CoreBundle\Event;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
    private $doctrine;
    private $logger;

    public function __construct($doctrine, $logger)
    {
    $this->doctrine = $doctrine;
    $this->logger = $logger;
    }

    public function getDoctrine()
    {
    return $this->doctrine;
    }

    public function getLogger()
    {
    return $this->logger;
    }


    public function loadEvents(CalendarEvent $calendarEvent)
    {
    $this->getLogger()->info('CalendarEventListener.loadEvents 1');

    $entityManager = $this->getDoctrine()->getEntityManager();

	$startDate = $calendarEvent->getStartDatetime();
	$endDate = $calendarEvent->getEndDatetime();

	// The original request so you can get filters from the calendar
	// Use the filter in your query for example
	$request = $calendarEvent->getRequest();
	$filter = $request->get('filter');


	// load events using your custom logic here,
	// for instance, retrieving events from a repository

	$SBEvents = $entityManager->getRepository('SDCoreBundle:QueryBooking')
		->createQueryBuilder('qb')
		->getQuery()->getResult();

	foreach($SBEvents as $SBEvent) {

		$eventEntity = new EventEntity($SBEvent->getName(), $SBEvent->get_tprr_createdAt(), $SBEvent->get_tprr_updatedAt());

		//optional calendar event settings
		// $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
		$eventEntity->setBgColor('#FF0000'); //set the background color of the event's label
		$eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
		$eventEntity->setUrl('../queryBooking/edit/12'); // url to send user to when event label is clicked
		// $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

		//finally, add the event to the CalendarEvent for displaying on the calendar
		$calendarEvent->addEvent($eventEntity);
	}
    }
}
