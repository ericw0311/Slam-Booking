<?php
// src/CoreBundle/DataFixtures/ORM/LoadActivity.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\Activity;

class LoadActivity extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-614')); $activity->setName('Réunion'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-127', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-614')); $activity->setName('Séminaire'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-128', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-614')); $activity->setName('Formation'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-129', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-321')); $activity->setName('Essais BE'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-5', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-321')); $activity->setName('Course sur Lorient'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-6', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-321')); $activity->setName('CP/RTT'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-7', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-492')); $activity->setName('Ecole de Tennis'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-48', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-492')); $activity->setName('Tournoi'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-49', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-440')); $activity->setName('Bernard DESMARET'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-54', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Réservation terrain'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-55', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Entraînements adultes'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-58', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Ecole de Tennis et/ou entraînements'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-59', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-523')); $activity->setName('Informatique'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-65', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Matchs par équipe'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-76', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-612')); $activity->setName('Squash'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-114', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Tournoi Interne'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-91', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-614')); $activity->setName('Cours'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-117', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-614')); $activity->setName('Visite'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-121', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Champ Ain Indiv'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-136', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Matchs par équipe jeunes'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-137', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('Entretien'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-140', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-612')); $activity->setName('Cours stretching'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-143', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-707')); $activity->setName('Cours Enfants'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-151', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-707')); $activity->setName('Cours adultes'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-152', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-707')); $activity->setName('Pas d\'accès possible'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-153', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('réservation clients extérieurs'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-157', $activity);
$activity = new Activity($this->getReference('user-1'), $this->getReference('file-507')); $activity->setName('TOURNOI OPEN TBB'); $manager->persist($activity); $manager->flush(); $this->addReference('activity-158', $activity);
    }


    public function getOrder()
    {
    return 5;
    }
}
