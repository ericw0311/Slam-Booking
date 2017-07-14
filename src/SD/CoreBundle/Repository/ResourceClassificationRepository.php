<?php

namespace SD\CoreBundle\Repository;

/**
 * ResourceClassificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResourceClassificationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getResourceClassificationCodes($file, $resourceType, $active)
    {
    $queryBuilder = $this->createQueryBuilder('rc');
    $queryBuilder->where('rc.file = :file')->setParameter('file', $file);
    $queryBuilder->andWhere('rc.type = :type')->setParameter('type', $resourceType);
    $queryBuilder->andWhere('rc.active = :active')->setParameter('active', $active);
   
    $query = $queryBuilder->getQuery();
    $results = $query->getResult();


	// On retourne un tableau des codes classifications sélectionnés
	$resourceClassificationCodes = array();

    foreach ($results as $resourceClassification) {
		array_push($resourceClassificationCodes, $resourceClassification->getCode());
	}

    return $resourceClassificationCodes;
    }
}
