<?php
// src/CoreBundle/DataFixtures/ORM/LoadResourceClassification.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\ResourceClassification;

class LoadResourceClassification extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$resourceClassification = new ResourceClassification($this->getReference('user-1'), $this->getReference('file-612')); $resourceClassification->setType('PLACE'); $resourceClassification->setInternal(1); $resourceClassification->setCode('HOUSE'); $resourceClassification->setName('HOUSE'); $resourceClassification->setActive(0); $manager->persist($resourceClassification); $manager->flush(); $this->addReference('resourceClassification-7829', $resourceClassification);
$resourceClassification = new ResourceClassification($this->getReference('user-1'), $this->getReference('file-612')); $resourceClassification->setType('PLACE'); $resourceClassification->setInternal(1); $resourceClassification->setCode('ROOM'); $resourceClassification->setName('ROOM'); $resourceClassification->setActive(0); $manager->persist($resourceClassification); $manager->flush(); $this->addReference('resourceClassification-7827', $resourceClassification);
$resourceClassification = new ResourceClassification($this->getReference('user-1'), $this->getReference('file-612')); $resourceClassification->setType('SPORT'); $resourceClassification->setInternal(1); $resourceClassification->setCode('GYMNASIUM'); $resourceClassification->setName('GYMNASIUM'); $resourceClassification->setActive(0); $manager->persist($resourceClassification); $manager->flush(); $this->addReference('resourceClassification-7845', $resourceClassification);
    }


    public function getOrder()
    {
    return 8;
    }
}
