<?php

namespace SD\CoreBundle\Entity;

class Constants
{
	const ACTIVITY_NUMBER_COLUMNS = 2;
	const ACTIVITY_NUMBER_LINES = 20;
	const BOOKING_NUMBER_COLUMNS = 2;
	const BOOKING_NUMBER_LINES = 20;
	const FILE_NUMBER_COLUMNS = 2;
	const FILE_NUMBER_LINES = 20;
	const LABEL_NUMBER_COLUMNS = 2;
	const LABEL_NUMBER_LINES = 20;
	const PLANIFICATION_NUMBER_COLUMNS = 2;
	const PLANIFICATION_NUMBER_LINES = 20;
	const QUERYBOOKING_NUMBER_COLUMNS = 2;
	const QUERYBOOKING_NUMBER_LINES = 20;
	const RESOURCE_NUMBER_COLUMNS = 2;
	const RESOURCE_NUMBER_LINES = 20;
	const TIMETABLE_NUMBER_COLUMNS = 2;
	const TIMETABLE_NUMBER_LINES = 20;
	const USERFILE_NUMBER_COLUMNS = 2;
	const USERFILE_NUMBER_LINES = 20;

	const NUMBER_LINES_BEFORE_AFTER_UPDATE = 3; // Nombre de lignes a afficher avant et apres la ligne mise a jour (cas de multilignes type creneaux horaires)
	const NUMBER_LINES_MINI_DUAL_BUTTON_LIST = 8; // Nombre de lignes minimum a partir duquel on affiche la serie de bouttons actions avant et après la liste en question

	const RESOURCE_TYPE = array('PLACE', 'VEHICLE', 'TOOL', 'SPORT', 'USER');
	
	const DISPLAYED_RESOURCE_TYPE = array('PLACE', 'VEHICLE', 'TOOL', 'SPORT');
	
	const RESOURCE_TYPE_ICON = array(
			'PLACE' => 'place',
			'VEHICLE' => 'vehicle',
			'TOOL' => 'tool',
			'SPORT' => 'sport',
			'USER' => 'user'
		);

	const RESOURCE_CLASSIFICATION = array(
			'PLACE' => array('ROOM', 'FLAT', 'HOUSE', 'MOBILE-HOME', 'TENT'),
			'VEHICLE' => array('CAR', 'TRUCK', 'TRACTOR', 'BIKE', 'MOTORBIKE', 'BOAT', 'PLANE', 'GLIDER'),
			'TOOL' => array('COMPUTER', 'CAMERA', 'PROJECTOR'),
			'SPORT' => array('COURT', 'PITCH', 'GYMNASIUM', 'HORSE'),
			'USER' => array('TEACHER', 'CONTRACTOR', 'DOCTOR', 'DENTIST')
		);

	const RESOURCE_CLASSIFICATION_ACTIVE = array(
			'PLACE' => array('ROOM', 'HOUSE'),
			'VEHICLE' => array('CAR'),
			'TOOL' => array('COMPUTER'),
			'SPORT' => array('COURT', 'GYMNASIUM'),
			'USER' => array('TEACHER')
		);

	const RESOURCE_CLASSIFICATION_ICON = array(
			'BIKE' => 'bike',
			'BOAT' => 'boat',
			'CAMERA' => 'camera',
			'CAR' => 'car',
			'COMPUTER' => 'computer',
			'CONTRACTOR' => 'contractor',
			'COURT' => 'court',
			'DENTIST' => 'dentist',
			'DOCTOR' => 'doctor',
			'FLAT' => 'flat',
			'GLIDER' => 'glider',
			'GYMNASIUM' => 'gymnasium',
			'HORSE' => 'horse',
			'HOUSE' => 'house',
			'MOBILE-HOME' => 'mobile-home',
			'MOTORBIKE' => 'motorbike',
			'PITCH' => 'pitch',
			'PLANE' => 'plane',
			'PROJECTOR' => 'projector',
			'ROOM' => 'room',
			'TEACHER' => 'teacher',
			'TENT' => 'tent',
			'TRACTOR' => 'tractor',
			'TRUCK' => 'truck'
		);


	// Indique les routes de création et d'affichage de la liste par entité.
	// Elles sont toutes construites sur le même principe à l'exception de l'entité "booking" car les réservations sont affichées dans le controller "planning"
	// De plus, il n'y a pas de route de création depuis les listes de réservations
	const ENTITY_PATHS = array(
		'activity' => array('add' => 'sd_core_activity_add', 'list' => 'sd_core_label_list', 'display_add' => true),
		'booking' => array('add' => 'sd_core_booking_add', 'list' => 'sd_core_planning_all_booking_list', 'display_add' => false),
		'file' => array('add' => 'sd_core_file_add', 'list' => 'sd_core_file_list', 'display_add' => true),
		'label' => array('add' => 'sd_core_label_add', 'list' => 'sd_core_label_list', 'display_add' => true),
		'planification' => array('add' => 'sd_core_planification_type', 'list' => 'sd_core_planification_list', 'display_add' => true),
		'queryBooking' => array('add' => 'sd_core_queryBooking_add', 'list' => 'sd_core_queryBooking_list', 'display_add' => true),
		'resource' => array('add' => 'sd_core_resource_classification', 'list' => 'sd_core_resource_list', 'display_add' => true),
		'timetable' => array('add' => 'sd_core_timetable_add', 'list' => 'sd_core_timetable_list', 'display_add' => true),
		'userFile' => array('add' => 'sd_core_userFile_email', 'list' => 'sd_core_userFile_list', 'display_add' => true)
	);

	const WEEK_DAY_CODE = array(
			1 => 'MON',
			2 => 'TUE',
			3 => 'WED',
			4 => 'THU',
			5 => 'FRI',
			6 => 'SAT',
			7 => 'SUN');

	const MAXIMUM_NUMBER_BOOKING_LINES = 50; // Nombre maximum de lignes dans une réservation
	const MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED = 5; // Nombre maximum de dates affichées (utilisé pour la mise a jour des périodes de début et de fin des réservations)

	// Les couleurs d'affichage des réservations dans le calendrier: blanc sur bleu, noir sur rouge, noir sur vert
	const CALENDAR_RESOURCE_COLOR = array(
		1 => array('BGC' => '#0033FF', 'FGC' => '#ffffff'),
		2 => array('BGC' => '#CC0000', 'FGC' => '#000000'),
		0 => array('BGC' => '#669900', 'FGC' => '#000000')
	);

	// La couleur par défaut d'affichage des réservations dans le calendrier: noir sur rose POUR L'INSTANT
	const CALENDAR_RESOURCE_DEFAULT_COLOR = array('BGC' => '#ff69b4', 'FGC' => '#000000');
}
