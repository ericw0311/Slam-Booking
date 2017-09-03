<?php

namespace SD\CoreBundle\Repository;

/**
 * PlanificationLineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanificationLineRepository extends \Doctrine\ORM\EntityRepository
{
	// Recherche les lignes d'une periode de planification
	public function getLines($planificationPeriod)
    {
    $queryBuilder = $this->createQueryBuilder('pl');
    $queryBuilder->where('pl.planificationPeriod = :planificationPeriod')->setParameter('planificationPeriod', $planificationPeriod);
    $queryBuilder->orderBy('pl.order', 'ASC');
   
    $query = $queryBuilder->getQuery();
    $results = $query->getResult();
    return $results;
    }
}