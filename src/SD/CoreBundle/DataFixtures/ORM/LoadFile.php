<?php
// src/CoreBundle/DataFixtures/ORM/LoadFile.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\File;

class LoadFile extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$fileAdmin = new File($this->getReference('user-527')); $fileAdmin->setName('RESERVATIONS'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-321', $fileAdmin);
$fileAdmin = new File($this->getReference('user-738')); $fileAdmin->setName('Réservation'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-440', $fileAdmin);
$fileAdmin = new File($this->getReference('user-843')); $fileAdmin->setName('Tennis Club de Veurey Voroize'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-492', $fileAdmin);
$fileAdmin = new File($this->getReference('user-860')); $fileAdmin->setName('Tie Break Beynolan'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-507', $fileAdmin);
$fileAdmin = new File($this->getReference('user-865')); $fileAdmin->setName('Réservation salles'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-516', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1022')); $fileAdmin->setName('Salle Sacré Coeur'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-523', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1077')); $fileAdmin->setName('COURTS DE TENNIS DE MARNAY'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-535', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1095')); $fileAdmin->setName('Aeroclub de  Gray'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-538', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1139')); $fileAdmin->setName('Le Plan B.'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-550', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1160')); $fileAdmin->setName('ACSRC reservations'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-555', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1323')); $fileAdmin->setName('RESERVATION de SALLES'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-598', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1408')); $fileAdmin->setName('SQUASH'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-612', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1418')); $fileAdmin->setName('Gestion'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-614', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1602')); $fileAdmin->setName('SallereunionLevallois'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-690', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1602')); $fileAdmin->setName('Conf Call KPC'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-691', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1661')); $fileAdmin->setName('ASRV tennis'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-707', $fileAdmin);
$fileAdmin = new File($this->getReference('user-1868')); $fileAdmin->setName('Salles des travaux pratiques sciences'); $manager->persist($fileAdmin); $manager->flush(); $this->addReference('file-757', $fileAdmin);
    }

    public function getOrder()
    {
    return 2;
    }
}
