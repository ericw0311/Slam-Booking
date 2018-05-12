<?php
// src/SD/CoreBundle/Api/ResourceApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\ResourceNDBPlanificationAdd;
use SD\CoreBundle\Entity\ResourceNDBPlanificationSelected;
use SD\CoreBundle\Entity\Constants;

class ResourceApi
{
	// Retourne un tableau du nombre de ressources par classification interne
    static function getInternalClassificationNumberResources($em, \SD\CoreBundle\Entity\File $file, $resourceType)
    {
	$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$userFileRepository = $em->getRepository('SDCoreBundle:UserFile');

    $numberResources = array();

    foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $rcCode) {
		if ($resourceType == 'USER') {
			$numberResources[$rcCode] = $userFileRepository->getUserFilesCountFrom_IRC($file, $rcCode);
		} else {
			$numberResources[$rcCode] = $resourceRepository->getResourcesCount_IRC($file, $resourceType, $rcCode);
		}
	}

	return $numberResources;
    }

	// Retourne un tableau du nombre de ressources par classification externe
    static function getExternalClassificationNumberResources($em, \SD\CoreBundle\Entity\File $file, $resourceType, $listExternalRC)
    {
	$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$userFileRepository = $em->getRepository('SDCoreBundle:UserFile');

    $numberResources = array();

    foreach ($listExternalRC as $i_ERC) {
		if ($resourceType == 'USER') {
			$numberResources[$i_ERC->getID()] = $userFileRepository->getUserFilesCountFrom_ERC($file, $i_ERC);
		} else {
			$numberResources[$i_ERC->getID()] = $resourceRepository->getResourcesCount_ERC($file, $resourceType, $i_ERC);
		}
	}

	return $numberResources;
    }

	// Retourne un tableau des classifications de ressources internes actives
    static function getActiveInternalResourceClassifications($em, \SD\CoreBundle\Entity\File $file, $resourceType)
    {
    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	$activeInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($file, $resourceType, 1); // Classifications internes actives (lues en BD)
	$unactiveInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($file, $resourceType, 0); // Classifications internes inactives (lues en BD)

    $defaultActiveRC = Constants::RESOURCE_CLASSIFICATION_ACTIVE[$resourceType]; // Classifications actives par défaut

	// Les classifications actives sont celles qui sont actives par defaut ou actives en base et qui ne sont pas inactives en base
    $activeInternalRC = array();

    foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $resourceClassification) {
		if ((in_array($resourceClassification, $defaultActiveRC) || in_array($resourceClassification, $activeInternalRC_DB))
			&& !in_array($resourceClassification, $unactiveInternalRC_DB))
		{
			array_push($activeInternalRC, $resourceClassification);
		}
	}
	return $activeInternalRC;
    }


	// Retourne la premiere classification de ressources interne active (ou N si non trouvee)
    static function getFirstActiveInternalResourceClassification($em, \SD\CoreBundle\Entity\File $file, $resourceType)
    {
    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	$activeInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($file, $resourceType, 1); // Classifications internes actives (lues en BD)
	$unactiveInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($file, $resourceType, 0); // Classifications internes inactives (lues en BD)

    $defaultActiveRC = Constants::RESOURCE_CLASSIFICATION_ACTIVE[$resourceType]; // Classifications actives par défaut

	// Les classifications actives sont celles qui sont actives par defaut ou actives en base et qui ne sont pas inactives en base
    $activeInternalRC = array();

    foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $resourceClassification) {
		if ((in_array($resourceClassification, $defaultActiveRC) || in_array($resourceClassification, $activeInternalRC_DB))
			&& !in_array($resourceClassification, $unactiveInternalRC_DB))
		{
			return $resourceClassification;
		}
	}
	return 'N';
    }


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
    static function getResourcesToPlanify($resourcesToPlanifyDB, $selectedResourcesID)
    {
	$selectedResourcesIDArray = explode('-', $selectedResourcesID);

	$resourcesToPlanify = array();
    foreach ($resourcesToPlanifyDB as $resourceDB) {
		if (array_search($resourceDB->getId(), $selectedResourcesIDArray) === false) {
			$resource = new ResourceNDBPlanificationAdd(); // classe ressource incluant les infos spécifiques aux ressources pouvant être ajoutées à la sélection
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());
			$resource->setResourceIDList_select(($selectedResourcesID == '') ? $resourceDB->getId() : ($selectedResourcesID.'-'.$resourceDB->getId())); // Liste des ressources sélectionnées si l'utilisateur sélectionne la ressource
			array_push($resourcesToPlanify, $resource);
		}
	}
	return $resourcesToPlanify;
    }

    // Retourne un tableau des ressources à planifier (initialisation de planification)
    static function initResourcesToPlanify($em, \SD\CoreBundle\Entity\File $file, $type, $selectedResourcesID)
    {
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');

    $resourcesToPlanifyDB = $resourceRepository->getResourcesToPlanify($file, $type, $planificationResourceRepository->getResourcePlanifiedQB());

	return ResourceApi::getResourcesToPlanify($resourcesToPlanifyDB, $selectedResourcesID);
    }

    // Retourne un tableau des ressources à planifier (mise à jour de planification)
    static function updateResourcesToPlanify($em, \SD\CoreBundle\Entity\File $file, $type, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod, $selectedResourcesID)
    {
	$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');

    $resourcesToPlanifyDB = $resourceRepository->getResourcesToPlanify($file, $type, $planificationResourceRepository->getResourcePlanifiedExcludePeriodQB($planificationPeriod));

	return ResourceApi::getResourcesToPlanify($resourcesToPlanifyDB, $selectedResourcesID);
    }

	// Retourne un tableau des ressources paires d'une periode de planification
	static function getEvenPlanifiedResourcesID($em, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
	{
    $prRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $prRepository->getResources($planificationPeriod);

	$resources = array();
	$even = false;

	foreach ($planificationResources as $planificationResource) {

		if ($even) {
			$resources[] = $planificationResource->getResource()->getID();
			$even = false;
		} else {
			$even = true;
		}
	}
	
	return $resources;
	}

	// Retourne un tableau des ressources d'une planification indiquant pour chacune l'indice de la couleur d'affichage dans le calendrier
	static function getCalendarResourcesColor($em, \SD\CoreBundle\Entity\Planification $planification)
	{
    $ppRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');
    $prRepository = $em->getRepository('SDCoreBundle:PlanificationResource');

	$planificationPeriod = $ppRepository->getLastPlanificationPeriod($planification);

    $planificationResources = $prRepository->getResources($planificationPeriod);
	$numberOfColors = count(Constants::CALENDAR_RESOURCE_COLOR); // Nombre de couleurs différentes pour l'affichage des ressources dans le calendrier
 
	$resources = array();
	$i = 0;

	foreach ($planificationResources as $planificationResource) {
		$resources[$planificationResource->getResource()->getID()] = (++$i % $numberOfColors);
	}
	return $resources;
	}
}
