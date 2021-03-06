<?php

namespace SD\CoreBundle\Repository;

use Doctrine\ORM\Query\Expr;

/**
 * UserFileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserFileRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserFilesCount($file)
    {
    $qb = $this->createQueryBuilder('uf');
    $qb->select($qb->expr()->count('uf'));
    $qb->where('uf.file = :file')->setParameter('file', $file);

    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

	public function getUserFiles($file)
    {
    $qb = $this->createQueryBuilder('uf');
    $qb->where('uf.file = :file')->setParameter('file', $file);
    $qb->orderBy('uf.firstName', 'ASC');
    $qb->addOrderBy('uf.lastName', 'ASC');
   
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

    public function getUserFilesExceptFileCreatorCount($file)
    {
    $qb = $this->createQueryBuilder('uf');
    $qb->select($qb->expr()->count('uf'));
    $qb->where('uf.file = :file')->setParameter('file', $file);
	$qb->andWhere($qb->expr()->not($qb->expr()->eq('uf.account', '?1')));
	$qb->setParameter(1, $file->getUser()); 

    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

    public function getUserFilesExceptFileCreator($file)
    {
    $qb = $this->createQueryBuilder('uf');
    $qb->where('uf.file = :file')->setParameter('file', $file);
	$qb->andWhere($qb->expr()->not($qb->expr()->eq('uf.account', '?1')));
	$qb->setParameter(1, $file->getUser()); 
    $qb->orderBy('uf.firstName', 'ASC');
    $qb->addOrderBy('uf.lastName', 'ASC');
   
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

    public function getDisplayedUserFiles($file, $firstRecordIndex, $maxRecord)
    {
    $qb = $this->createQueryBuilder('uf');
    $qb->where('uf.file = :file')->setParameter('file', $file);
    $qb->orderBy('uf.firstName', 'ASC');
    $qb->addOrderBy('uf.lastName', 'ASC');
    $qb->setFirstResult($firstRecordIndex);
    $qb->setMaxResults($maxRecord);
   
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

	// Retourne le nombre d'utilisateurs ressources d'une classification interne
	public function getUserFilesCountFrom_IRC($file, $resourceClassificationCode)
    {
    $resourceType = 'USER';

    $qb = $this->createQueryBuilder('uf');
    $qb->select($qb->expr()->count('uf'));
    $qb->where('uf.file = :file')->setParameter('file', $file);
    $qb->andWhere('uf.resourceUser = :resourceUser')->setParameter('resourceUser', 1);
	$qb->innerJoin('uf.resource', 'r', Expr\Join::WITH, $qb->expr()->andX($qb->expr()->eq('r.internal', '?1'), $qb->expr()->eq('r.type', '?2'), $qb->expr()->eq('r.code', '?3')));
    $qb->setParameter(1, 1);
    $qb->setParameter(2, $resourceType);
    $qb->setParameter(3, $resourceClassificationCode);

    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

	// Retourne les utilisateurs ressources d'une classification interne
	public function getUserFilesFrom_IRC($file, $resourceClassificationCode)
    {
    $resourceType = 'USER';

	$qb = $this->createQueryBuilder('uf');
    $qb->where('uf.file = :file')->setParameter('file', $file);
    $qb->andWhere('uf.resourceUser = :resourceUser')->setParameter('resourceUser', 1);
	$qb->innerJoin('uf.resource', 'r', Expr\Join::WITH, $qb->expr()->andX($qb->expr()->eq('r.internal', '?1'), $qb->expr()->eq('r.type', '?2'), $qb->expr()->eq('r.code', '?3')));
    $qb->orderBy('uf.firstName', 'ASC');
    $qb->addOrderBy('uf.lastName', 'ASC');
    $qb->setParameter(1, 1);
    $qb->setParameter(2, $resourceType);
    $qb->setParameter(3, $resourceClassificationCode);
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

	// Retourne le nombre d'utilisateurs ressources d'une classification externe
	public function getUserFilesCountFrom_ERC($file, $resourceClassification)
    {
    $resourceType = 'USER';

    $qb = $this->createQueryBuilder('uf');
    $qb->select($qb->expr()->count('uf'));
	$qb->where('uf.file = :file')->setParameter('file', $file);
    $qb->andWhere('uf.resourceUser = :resourceUser')->setParameter('resourceUser', 1);
	$qb->innerJoin('uf.resource', 'r', Expr\Join::WITH, $qb->expr()->andX($qb->expr()->eq('r.internal', '?1'), $qb->expr()->eq('r.type', '?2'), $qb->expr()->eq('r.classification', '?3')));
    $qb->setParameter(1, 0);
    $qb->setParameter(2, $resourceType);
    $qb->setParameter(3, $resourceClassification);

    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

    // Retourne les utilisateurs ressources d'une classification externe
    public function getUserFilesFrom_ERC($file, $resourceClassification)
    {
    $resourceType = 'USER';

	$qb = $this->createQueryBuilder('uf');
	$qb->where('uf.file = :file')->setParameter('file', $file);
    $qb->andWhere('uf.resourceUser = :resourceUser')->setParameter('resourceUser', 1);
	$qb->innerJoin('uf.resource', 'r', Expr\Join::WITH, $qb->expr()->andX($qb->expr()->eq('r.internal', '?1'), $qb->expr()->eq('r.type', '?2'), $qb->expr()->eq('r.classification', '?3')));
    $qb->orderBy('uf.firstName', 'ASC');
    $qb->addOrderBy('uf.lastName', 'ASC');
    $qb->setParameter(1, 0);
    $qb->setParameter(2, $resourceType);
    $qb->setParameter(3, $resourceClassification);
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }
}
