<?php

namespace SD\CoreBundle\Repository;

/**
 * ResourceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResourceRepository extends \Doctrine\ORM\EntityRepository
{
    public function getResourcesCount($file)
    {
    $queryBuilder = $this->createQueryBuilder('r');
    $queryBuilder->select($queryBuilder->expr()->count('r'));
    $queryBuilder->where('r.file = :file')->setParameter('file', $file);

    $query = $queryBuilder->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }
	
	public function getDisplayedResources($file, $firstRecordIndex, $maxRecord)
    {
    $queryBuilder = $this->createQueryBuilder('r');
    $queryBuilder->where('r.file = :file')->setParameter('file', $file);
    $queryBuilder->orderBy('r.name', 'ASC');
    $queryBuilder->setFirstResult($firstRecordIndex);
    $queryBuilder->setMaxResults($maxRecord);
   
    $query = $queryBuilder->getQuery();
    $results = $query->getResult();
    return $results;
    }

	public function getResourcesToPlanify($file, $type)
    {
    $queryBuilder = $this->createQueryBuilder('r');
    $queryBuilder->where('r.file = :file')->setParameter('file', $file);
    $queryBuilder->andWhere('r.type = :type')->setParameter('type', $type);
    $queryBuilder->orderBy('r.name', 'ASC');

    $query = $queryBuilder->getQuery();
    $results = $query->getResult();
    return $results;
    }

    // Retourne le nombre de ressources d'une classification
    public function getResourcesCount_RC($resourceClassification)
    {
    $queryBuilder = $this->createQueryBuilder('r');
    $queryBuilder->select($queryBuilder->expr()->count('r'));
    $queryBuilder->where('r.classification = :classification')->setParameter('classification', $resourceClassification);

    $query = $queryBuilder->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

    // Retourne les ressources d'une classification
    public function getResources_RC($resourceClassification)
    {
	$queryBuilder = $this->createQueryBuilder('r');
    $queryBuilder->where('r.classification = :classification')->setParameter('classification', $resourceClassification);
	$queryBuilder->orderBy('r.name', 'ASC');

	$query = $queryBuilder->getQuery();
	$results = $query->getResult();
	return $results;
    }
}
