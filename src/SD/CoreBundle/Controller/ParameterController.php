<?php
// src/SD/CoreBundle/Controller/ParameterController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserParameterNLC;
use SD\CoreBundle\Entity\Constants;
use SD\CoreBundle\Entity\UserContext;

use SD\CoreBundle\Form\UserParameterNLCType;

class ParameterController extends Controller
{
	// Met a jour le nombre de lignes et le nombre de colonnes pour l'affichage d'une liste d'entites
	// bundleCode: Code du composant de l'entité
	// entityCode: Code de la liste affichee (logiquement, c'est le code de l'entite elle meme)
    public function numberLinesColumnsAction($bundleCode, $entityCode, $listPath, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $em = $this->getDoctrine()->getManager();
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');

    $userParameterNLFound = false; // Le parametre nombre de lignes est-il present ?
	$userParameterNL = $userParameterRepository->findOneBy(array('user' => $connectedUser, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.lines')));
	if ($userParameterNL != null) { $userParameterNLFound = true; $numberLines = $userParameterNL->getIntegerValue(); } else { $numberLines =  constant(Constants::class.'::'.strtoupper($entityCode).'_NUMBER_LINES'); }

    $userParameterNCFound = false; // Le parametre nombre de colonnes est-il present ?
	$userParameterNC = $userParameterRepository->findOneBy(array('user' => $connectedUser, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.columns')));
	if ($userParameterNC != null) { $userParameterNCFound = true; $numberColumns = $userParameterNC->getIntegerValue(); } else { $numberColumns =  constant(Constants::class.'::'.strtoupper($entityCode).'_NUMBER_COLUMNS'); }

    $userParameterNLC = new UserParameterNLC($numberLines, $numberColumns);
    $form = $this->createForm(UserParameterNLCType::class, $userParameterNLC);
	
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

		$numberLines = $userParameterNLC->getNumberLines();
		$numberColumns = $userParameterNLC->getNumberColumns();

		if ($userParameterNLFound) {
			$userParameterNL->setSDIntegerValue($numberLines);
		} else {
			$userParameterNL = new UserParameter($connectedUser, $entityCode.'.number.lines.columns', $entityCode.'.number.lines');
			$userParameterNL->setSDIntegerValue($numberLines);
			$em->persist($userParameterNL);
		}

		if ($userParameterNCFound) {
			$userParameterNC->setSDIntegerValue($numberColumns);
		} else {
			$userParameterNC = new UserParameter($connectedUser, $entityCode.'.number.lines.columns', $entityCode.'.number.columns');
			$userParameterNC->setSDIntegerValue($numberColumns);
			$em->persist($userParameterNC);
		}

		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'number.lines.columns.updated.ok');
		return $this->redirectToRoute($listPath, array('pageNumber' => 1));
	}

	return $this->render('SDCoreBundle:Parameter:numberLinesColumns.html.twig', array('userContext' => $userContext, 'entityCode' => $entityCode, 'listPath' => $listPath, 'form' => $form->createView()));
	}
}
