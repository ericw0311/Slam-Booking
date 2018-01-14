<?php
// src/SD/CoreBundle/Twig/SDExtension.php

namespace SD\CoreBundle\Twig;

class SDExtension extends \Twig_Extension
{
    private $translator;

    public function __construct($translator)
    {
    $this->translator = $translator;
    }

    public function getTranslator()
    {
    return $this->translator;
    }

	// J'ai gardé le filtre de l'exemple mais je ne l'utilise pas...
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
		$price = number_format($number, $decimals, $decPoint, $thousandsSep);
		$price = '$'.$price;
		return $price;
    }


	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dateCourte', array($this, 'date_courte')),
            new \Twig_SimpleFunction('dateLongue', array($this, 'date_longue')),
            new \Twig_SimpleFunction('timetableLine', array($this, 'timetable_line')),
        );
    }


	// Retourne une date courte. exemple: Vendredi 12 janvier 2018 --> Ven 12/01/18
    public function date_courte(\Datetime $date)
    {
		return $this->getTranslator()->trans('day.abbr.'.strtoupper($date->format('D'))).' '.$date->format('d/m/y');
    }

	// Retourne une date longue. exemple: Vendredi 12 janvier 2018
    public function date_longue(\Datetime $date)
    {
		return $this->getTranslator()->trans('day.'.strtoupper($date->format('D'))).' '.$date->format('d/m/Y');
    }

	// Retourne un creneau horaire
    public function timetable_line(\SD\CoreBundle\Entity\TimetableLine $timetableLine)
    {
		return $timetableLine->getBeginningTime()->format('H:i').' - '.$timetableLine->getEndTime()->format('H:i');
    }

    public function getName()
    {
        return 'sd_extension';
    }
}
