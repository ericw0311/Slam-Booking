<?php
// src/CoreBundle/DataFixtures/ORM/LoadPlanification.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\PlanificationPeriod;

class LoadPlanificationPeriod extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
// Pas de date
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-99')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-110', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-100')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-111', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-101')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-112', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-102')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-113', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-103')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-114', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-112')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-123', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-177')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-194', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-508')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-558', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-247')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-267', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-338')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-360', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-245')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-265', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-248')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-268', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-249')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-269', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-250')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-270', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-251')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-271', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-253')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-273', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-256')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-276', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-257')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-277', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-258')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-278', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-260')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-280', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-261')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-281', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-262')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-282', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-264')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-284', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-265')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-285', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-268')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-288', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-269')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-289', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-312')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-332', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-317')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-337', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-319')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-339', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-313')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-333', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-284')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-304', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-311')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-331', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-315')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-335', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-291')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-311', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-292')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-312', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-293')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-313', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-294')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-314', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-295')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-315', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-296')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-316', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-297')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-317', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-339')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-361', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-340')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-362', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-309')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-329', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-306')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-326', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-308')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-328', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-361')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-384', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-390')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-416', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-391')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-417', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-392')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-418', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-424')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-457', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-493')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-542', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-437')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-473', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-440')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-478', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-492')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-541', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-488')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-536', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-469')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-514', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-478')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-524', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-514')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-566', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-513')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-565', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-516')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-568', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-525')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-578', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-532')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-591', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-545')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-611', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-543')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-609', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-551')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-619', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-561')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-647', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-569')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-657', $planificationPeriod);

// Date de début
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-310')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','28/11/2014')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-385', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-396')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','28/06/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-452', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-421')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','04/07/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-492', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-252')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','12/12/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-581', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-482')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','23/05/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-605', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-550')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','07/09/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-618', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-377')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','05/10/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-620', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-409')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','24/09/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-621', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-316')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','01/03/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-623', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-263')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','04/11/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-624', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-288')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','27/02/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-625', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-276')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','24/03/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-643', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-266')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','01/05/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-644', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-314')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','15/04/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-646', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-244')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','01/04/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-648', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-477')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','07/07/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-655', $planificationPeriod);

// Date de fin
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-111')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','14/01/2014')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-122', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-244')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','31/03/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-264', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-252')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','11/12/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-272', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-310')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','27/11/2014')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-330', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-263')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','03/11/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-283', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-266')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','30/04/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-286', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-316')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','16/01/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-336', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-276')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','23/03/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-296', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-314')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','14/04/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-334', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-288')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','26/02/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-308', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-482')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','19/11/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-528', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-377')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','21/06/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-403', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-396')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','24/02/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-423', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-398')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','23/04/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-426', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-401')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','27/01/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-429', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-408')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','04/10/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-436', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-409')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','25/06/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-437', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-421')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','03/07/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-451', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-477')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','06/07/2017')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-523', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-550')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','06/09/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-617', $planificationPeriod);

// Dates de début et de fin
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-482')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','09/03/2016')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','07/04/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-592', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-482')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','20/11/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','08/03/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-529', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-316')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','17/01/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','29/02/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-399', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-396')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','25/02/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','27/06/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-424', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-398')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','24/04/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','23/06/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-463', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-377')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','31/12/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','04/10/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-552', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-409')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','26/06/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','23/09/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-475', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-377')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','22/06/2015')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','30/12/2015')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-476', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-482')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','08/04/2016')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','22/05/2016')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-597', $planificationPeriod);
$planificationPeriod = new PlanificationPeriod($this->getReference('user-1'), $this->getReference('planification-401')); $planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y','28/01/2017')); $planificationPeriod->setEndDate(date_create_from_format('d/m/Y','16/04/2018')); $manager->persist($planificationPeriod); $manager->flush(); $this->addReference('planificationHeader-631', $planificationPeriod);
    }


    public function getOrder()
    {
    return 11;
    }
}
