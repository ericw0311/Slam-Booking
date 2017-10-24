<?php
// src/SD/CoreBundle/Api/ResourceApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\ResourceNDBPlanificationAdd;
use SD\CoreBundle\Entity\ResourceNDBPlanificationSelected;

class ResourceApi
{
	// Retourne un tableau des ressources sélectionnées
	// resourceIDList: Liste des ID des ressources sélectionnées
	static function getSelectedResources($em, $resourceIDList)
	{
	$resourceIDArray = explode('-', $resourceIDList);
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

	$selectedResources = array();
	$i = 0;

    foreach ($resourceIDArray as $resourceID) {
		$resourceDB = $resourceRepository->find($resourceID);
		if ($resourceDB !== null) {
			$resource = new ResourceNDBPlanificationSelected(); // classe ressource incluant les infos spécifiques aux ressources sélectionnées
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());

			$resourceIDArray_tprr = $resourceIDArray;
			unset($resourceIDArray_tprr[$i]);
			$resource->setResourceIDList_unselect(implode('-', $resourceIDArray_tprr)); // Liste des ressources sélectionnées si l'utilisateur désélectionne la ressource

			if (count($resourceIDArray) > 1) {
				if ($i > 0) {
					$resourceIDArray_tprr = $resourceIDArray;
					$resourceIDArray_tprr[$i] = $resourceIDArray_tprr[$i-1];
					$resourceIDArray_tprr[$i-1] = $resourceID;
					$resource->setResourceIDList_sortBefore(implode('-', $resourceIDArray_tprr)); // Liste des ressources sélectionnées si l'utilisateur remonte la ressource dans l'ordre de tri
				}
				if ($i < count($resourceIDArray)-1) {
					$resourceIDArray_tprr = $resourceIDArray;
					$resourceIDArray_tprr[$i] = $resourceIDArray_tprr[$i+1];
					$resourceIDArray_tprr[$i+1] = $resourceID;
					$resource->setResourceIDList_sortAfter(implode('-', $resourceIDArray_tprr)); // Liste des ressources sélectionnées si l'utilisateur redescend la ressource dans l'ordre de tri
				}
			}
			$i++;
			array_push($selectedResources, $resource);
		}
	}
	return $selectedResources;
    }


    // Retourne un tableau des ressources à planifier
    static function getResourcesToPlanify($em, \SD\CoreBundle\Entity\File $file, $type, $resourceIDList)
    {
	$resourceIDArray = explode('-', $resourceIDList);
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');

    $resourcesToPlanifyDB = $resourceRepository->getResourcesToPlanify($file, $type, $planificationResourceRepository->getResourcePlanifiedQB());

	$resourcesToPlanify = array();
    foreach ($resourcesToPlanifyDB as $resourceDB) {
		if (array_search($resourceDB->getId(), $resourceIDArray) === false) {
			$resource = new ResourceNDBPlanificationAdd(); // classe ressource incluant les infos spécifiques aux ressources pouvant être ajoutées à la sélection
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());
			$resource->setResourceIDList_select(($resourceIDList == '') ? $resourceDB->getId() : ($resourceIDList.'-'.$resourceDB->getId())); // Liste des ressources sélectionnées si l'utilisateur sélectionne la ressource
			array_push($resourcesToPlanify, $resource);
		}
	}

	return $resourcesToPlanify;
    }
}
