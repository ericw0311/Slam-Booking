<?php

namespace SD\CoreBundle\Entity;

class Constants
{
	const ACTIVITY_NUMBER_COLUMNS = 2;
	const ACTIVITY_NUMBER_LINES = 20;
	const FILE_NUMBER_COLUMNS = 2;
	const FILE_NUMBER_LINES = 20;
	const TIMETABLE_NUMBER_COLUMNS = 2;
	const TIMETABLE_NUMBER_LINES = 20;
	const USERFILE_NUMBER_COLUMNS = 2;
	const USERFILE_NUMBER_LINES = 20;

        const NUMBER_LINES_BEFORE_AFTER_UPDATE = 3; // Nombre de lignes a afficher avant et apres la ligne mise a jour (cas de multilignes type creneaux horaires)
        const NUMBER_LINES_MINI_DUAL_BUTTON_LIST = 8; // Nombre de lignes minimum a partir duquel on affiche la serie de bouttons actions avant et après la liste en question
}
