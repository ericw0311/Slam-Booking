<?php
// src/CoreBundle/DataFixtures/ORM/LoadLabel.php
namespace UserBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SD\CoreBundle\Entity\Label;
class LoadLabel extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$label = new Label($this->getReference('user-1'), $this->getReference('file-321')); $label->setName('Essais BE'); $manager->persist($label); $manager->flush(); $this->addReference('activity-5', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-321')); $label->setName('Course sur Lorient'); $manager->persist($label); $manager->flush(); $this->addReference('activity-6', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-321')); $label->setName('CP/RTT'); $manager->persist($label); $manager->flush(); $this->addReference('activity-7', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-492')); $label->setName('Ecole de Tennis'); $manager->persist($label); $manager->flush(); $this->addReference('activity-48', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-492')); $label->setName('Tournoi'); $manager->persist($label); $manager->flush(); $this->addReference('activity-49', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-440')); $label->setName('Bernard DESMARET'); $manager->persist($label); $manager->flush(); $this->addReference('activity-54', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Réservation terrain'); $manager->persist($label); $manager->flush(); $this->addReference('activity-55', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Entraînements adultes'); $manager->persist($label); $manager->flush(); $this->addReference('activity-58', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Ecole de Tennis et/ou entraînements'); $manager->persist($label); $manager->flush(); $this->addReference('activity-59', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-523')); $label->setName('Informatique'); $manager->persist($label); $manager->flush(); $this->addReference('activity-65', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Matchs par équipe'); $manager->persist($label); $manager->flush(); $this->addReference('activity-76', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Tournoi Interne'); $manager->persist($label); $manager->flush(); $this->addReference('activity-91', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-612')); $label->setName('Squash'); $manager->persist($label); $manager->flush(); $this->addReference('activity-114', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Cours'); $manager->persist($label); $manager->flush(); $this->addReference('activity-117', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Visite'); $manager->persist($label); $manager->flush(); $this->addReference('activity-121', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Réunion'); $manager->persist($label); $manager->flush(); $this->addReference('activity-127', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Séminaire'); $manager->persist($label); $manager->flush(); $this->addReference('activity-128', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Formation'); $manager->persist($label); $manager->flush(); $this->addReference('activity-129', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Champ Ain Indiv'); $manager->persist($label); $manager->flush(); $this->addReference('activity-136', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Matchs par équipe jeunes'); $manager->persist($label); $manager->flush(); $this->addReference('activity-137', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Entretien'); $manager->persist($label); $manager->flush(); $this->addReference('activity-140', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-612')); $label->setName('Cours stretching'); $manager->persist($label); $manager->flush(); $this->addReference('activity-143', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-707')); $label->setName('Cours Enfants'); $manager->persist($label); $manager->flush(); $this->addReference('activity-151', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-707')); $label->setName('Cours adultes'); $manager->persist($label); $manager->flush(); $this->addReference('activity-152', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-707')); $label->setName('Pas d\'accès possible'); $manager->persist($label); $manager->flush(); $this->addReference('activity-153', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('réservation clients extérieurs'); $manager->persist($label); $manager->flush(); $this->addReference('activity-157', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('TOURNOI OPEN TBB'); $manager->persist($label); $manager->flush(); $this->addReference('activity-158', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-757')); $label->setName('TP chimie'); $manager->persist($label); $manager->flush(); $this->addReference('activity-163', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-757')); $label->setName('TP physique'); $manager->persist($label); $manager->flush(); $this->addReference('activity-164', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-757')); $label->setName('Manip bureau physique'); $manager->persist($label); $manager->flush(); $this->addReference('activity-165', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-757')); $label->setName('Manip bureau chimie'); $manager->persist($label); $manager->flush(); $this->addReference('activity-166', $label);
    }
    public function getOrder()
    {
    return 5;
    }
}
