<?php

namespace AlexBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AlexBundle\Form\PaysType;
use AlexBundle\Entity\Pays;

/**
 * @Route("/alex")
 */
class PaysController extends Controller
{
    /**
     * @Route("/pays", name="pays_list")
     * @Template()
     */
    public function paysAction(Request $request) {

        $em = $this->getDoctrine()->getEntityManager();

        $pays = new Pays();
        $form = $this->createForm(PaysType::class, $pays);
        $form->add('send', SubmitType::class, ['label' => 'Créer le pays']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // Upload...
            $file = $pays->getDrapeau();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            $pays->setDrapeau($fileName);

            $em->persist($pays);
            $em->flush();
            // success notice...
            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Pays ajouté');
        }

        $listePays = $em->getRepository('AlexBundle:Pays')->findAll();

        return $this->render('AlexBundle:Pays:listePays.html.twig', array(
            'listePays' => $listePays,
            'nouveauPays' => $form->createView()
        ));
    }

    /**
     * @Route("/pays/delete/{id}", name="pays_delete")
     * @Template()
     */
    public function deletePaysAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $pays = $em->getRepository('AlexBundle:Pays')->find($id);
        $em->remove($pays);
        $em->flush();
        //$session->getFlashBag()->add('success', 'Pays supprimé');
        return $this->redirectToRoute('pays_list');
    }

    /**
     * @Route("/pays/edit/{id}", name="pays_edit")
     * @Template()
     */
    public function editPaysAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $pays = $em->getRepository('AlexBundle:Pays')->find($id);
        //var_dump($pays);
        //die();

        $form = $this->createForm(PaysType::class, $pays);
        $form->add('send', SubmitType::class, ['label' => 'Editer le pays']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $file = $pays->getDrapeau();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            $pays->setDrapeau($fileName);

            $em->persist($pays);
            $em->flush();
            // success notice...
            //$session = $this->get('session');
            //$session->getFlashBag()->add('success', 'Pays édité');
        }

        return $this->render('AlexBundle:Pays:listePays.html.twig', array(
            'pays' => $pays,
            'formEditPays' => $form->createView()
        ));
    }
}

