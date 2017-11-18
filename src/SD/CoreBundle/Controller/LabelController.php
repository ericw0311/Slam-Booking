<?php
// src/SD/CoreBundle/Controller/LabelController.php
namespace SD\CoreBundle\Controller;
use SD\CoreBundle\Entity\Label;
use SD\CoreBundle\Form\LabelType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;
use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class LabelController extends Controller
{
	// Affichage des étiquettes du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $labelRepository = $em->getRepository('SDCoreBundle:Label');
    $numberRecords = $labelRepository->getLabelsCount($userContext->getCurrentFile());
    $listContext = new ListContext($em, $connectedUser, 'core', 'label', $pageNumber, $numberRecords, 'sd_core_label_list', 'sd_core_label_add');
    $listLabels = $labelRepository->getDisplayedLabels($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Label:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listLabels' => $listLabels));
    }
	// Ajout d'une étiquete
    public function addAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$label = new Label($connectedUser, $userContext->getCurrentFile());
	$form = $this->createForm(LabelType::class, $label);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($label);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'label.created.ok');
		return $this->redirectToRoute('sd_core_label_list', array('pageNumber' => 1));
	}
    return $this->render('SDCoreBundle:Label:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }
	
    // Edition du detail d'une étiquete
    /**
    * @ParamConverter("label", options={"mapping": {"labelID": "id"}})
    */
    public function editAction(Label $label)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    return $this->render('SDCoreBundle:Label:edit.html.twig', array('userContext' => $userContext, 'label' => $label));
    }
	
    // Modification d'une étiquete
    /**
    * @ParamConverter("label", options={"mapping": {"labelID": "id"}})
    */
    public function modifyAction(Label $label, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->createForm(LabelType::class, $label);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'étiquette
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'label.updated.ok');
        return $this->redirectToRoute('sd_core_label_edit', array('labelID' => $label->getId()));
    }
    return $this->render('SDCoreBundle:Label:modify.html.twig', array('userContext' => $userContext, 'label' => $label, 'form' => $form->createView()));
    }
    // Suppression d'une étiquete
    /**
    * @ParamConverter("label", options={"mapping": {"labelID": "id"}})
    */
    public function deleteAction(Label $label, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->get('form.factory')->create();
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'étiquette
        $em->remove($label);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'label.deleted.ok');
        return $this->redirectToRoute('sd_core_label_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Label:delete.html.twig', array('userContext' => $userContext, 'label' => $label, 'form' => $form->createView()));
    }
}