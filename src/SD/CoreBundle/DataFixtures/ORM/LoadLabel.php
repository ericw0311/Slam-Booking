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
$label = new Label($this->getReference('user-1'), $this->getReference('file-321')); $label->setName('Essais BE'); $manager->persist($label); $manager->flush(); $this->addReference('label-5', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-321')); $label->setName('Course sur Lorient'); $manager->persist($label); $manager->flush(); $this->addReference('label-6', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-321')); $label->setName('CP/RTT'); $manager->persist($label); $manager->flush(); $this->addReference('label-7', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-492')); $label->setName('Ecole de Tennis'); $manager->persist($label); $manager->flush(); $this->addReference('label-48', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-492')); $label->setName('Tournoi'); $manager->persist($label); $manager->flush(); $this->addReference('label-49', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-440')); $label->setName('Bernard DESMARET'); $manager->persist($label); $manager->flush(); $this->addReference('label-54', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Réservation terrain'); $manager->persist($label); $manager->flush(); $this->addReference('label-55', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Entraînements adultes'); $manager->persist($label); $manager->flush(); $this->addReference('label-58', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Ecole de Tennis et/ou entraînements'); $manager->persist($label); $manager->flush(); $this->addReference('label-59', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-523')); $label->setName('Informatique'); $manager->persist($label); $manager->flush(); $this->addReference('label-65', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Matchs par équipe'); $manager->persist($label); $manager->flush(); $this->addReference('label-76', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Tournoi Interne'); $manager->persist($label); $manager->flush(); $this->addReference('label-91', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-612')); $label->setName('Squash'); $manager->persist($label); $manager->flush(); $this->addReference('label-114', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Cours'); $manager->persist($label); $manager->flush(); $this->addReference('label-117', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Visite'); $manager->persist($label); $manager->flush(); $this->addReference('label-121', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Réunion'); $manager->persist($label); $manager->flush(); $this->addReference('label-127', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Séminaire'); $manager->persist($label); $manager->flush(); $this->addReference('label-128', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-614')); $label->setName('Formation'); $manager->persist($label); $manager->flush(); $this->addReference('label-129', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Champ Ain Indiv'); $manager->persist($label); $manager->flush(); $this->addReference('label-136', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Matchs par équipe jeunes'); $manager->persist($label); $manager->flush(); $this->addReference('label-137', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('Entretien'); $manager->persist($label); $manager->flush(); $this->addReference('label-140', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-612')); $label->setName('Cours stretching'); $manager->persist($label); $manager->flush(); $this->addReference('label-143', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-707')); $label->setName('Cours Enfants'); $manager->persist($label); $manager->flush(); $this->addReference('label-151', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-707')); $label->setName('Cours adultes'); $manager->persist($label); $manager->flush(); $this->addReference('label-152', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-707')); $label->setName('Pas d\'accès possible'); $manager->persist($label); $manager->flush(); $this->addReference('label-153', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('réservation clients extérieurs'); $manager->persist($label); $manager->flush(); $this->addReference('label-157', $label);
$label = new Label($this->getReference('user-1'), $this->getReference('file-507')); $label->setName('TOURNOI OPEN TBB'); $manager->persist($label); $manager->flush(); $this->addReference('label-158', $label);
    }
    public function getOrder()
    {
    return 5;
    }
}