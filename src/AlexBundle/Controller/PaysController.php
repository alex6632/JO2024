<?php

namespace AlexBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/{_locale}/pays", name="pays_list")
     * @Template()
     */
    public function paysAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();

        $pays = new Pays();
        $form = $this->createForm(PaysType::class, $pays);
        $form->add('send', SubmitType::class, ['label' => 'pays.form.create']);
        $form->handleRequest($request);
        $session = $this->get('session');

        if($form->isSubmitted() && $form->isValid()) {

            // Upload...
            $file = $pays->getDrapeau();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_drapeau'),
                $fileName
            );
            $pays->setDrapeau($fileName);

            $em->persist($pays);
            $em->flush();

            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('pays.create.succes')
            );
        }
        if($form->isSubmitted() && !$form->isValid()){
            $session->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('create.error')
            );
        }

        $listePays = $em->getRepository('AlexBundle:Pays')->findAll();

        return $this->render('AlexBundle:Pays:listePays.html.twig', array(
            'listePays' => $listePays,
            'nouveauPays' => $form->createView()
        ));
    }

    /**
     * @Route("/{_locale}/pays/delete/{id}", name="pays_delete")
     * @Template()
     */
    public function deletePaysAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $pays = $em->getRepository('AlexBundle:Pays')->find($id);

        $drapeau = $pays->getDrapeau();
        $drapeauPath = $this->get('kernel')->getRootDir().'/../web/images/uploads/drapeaux/' .$drapeau;
        unlink($drapeauPath);

        $em->remove($pays);
        $em->flush();

        $session = $this->get('session');
        $session->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('pays.delete.succes')
        );

        return $this->redirectToRoute('pays_list');
    }

    /**
     * @Route("/{_locale}/pays/edit/{id}", name="pays_edit")
     * @Template()
     */
    public function editPaysAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $pays = $em->getRepository('AlexBundle:Pays')->find($id);

        $form = $this->createForm(PaysType::class, $pays);
        $form->add('send', SubmitType::class, ['label' => 'edit.btn']);
        $form->handleRequest($request);
        $session = $this->get('session');

        if($form->isSubmitted() && $form->isValid()) {

            $file = $pays->getDrapeau();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_drapeau'),
                $fileName
            );
            $pays->setDrapeau($fileName);

            $em->persist($pays);
            $em->flush();

            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('pays.edit.succes')
            );

            return $this->redirectToRoute('pays_list');
        } else {
            // On supprime l'ancien drapeau
            $ancienDrapeau = $pays->getDrapeau();
            $ancienDrapeauPath = $this->get('kernel')->getRootDir().'/../web/images/uploads/drapeaux/'.$ancienDrapeau;
            unlink($ancienDrapeauPath);
        }
        if($form->isSubmitted() && !$form->isValid()){
            $session->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('edit.error')
            );
        }

        return $this->render('AlexBundle:Pays:editPays.html.twig', array(
            'pays' => $pays,
            'formEditPays' => $form->createView()
        ));
    }
}

