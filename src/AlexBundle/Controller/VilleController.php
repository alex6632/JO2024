<?php

namespace AlexBundle\Controller;

use AlexBundle\Entity\Ville;
use AlexBundle\Form\VilleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/alex")
 */
class VilleController extends Controller
{
    /**
     * @Route("/{_locale}/villes", name="villes_list")
     * @Template()
     */
    public function villeAction(Request $request) {

        $em = $this->getDoctrine()->getEntityManager();
        //$logger = $this->get('logger');
        $ville = new Ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->add('send', SubmitType::class, ['label' => 'ville.form.create']);
        $form->handleRequest($request);

        // ---------- SUBMIT FORM WITH AJAX ----------
        if($request->isXmlHttpRequest()) {
            $form = $request->get('ville');
            // dans var/logs/dev.log
            //$logger->error('FORM name : '.$form['name']);

            if(empty($form['nom'])) {
                $msg = array(
                    'type' => 'error',
                    'msg'  => $this->get('translator')->trans('ville.emptyName')
                );
            } else {
                $em->persist($ville);
                $em->flush();
                $msg = array(
                    'type'       => 'success',
                    'msg'        => $this->get('translator')->trans('ville.createdSuccess'),
                    'nom'        => $form['nom'],
                    'id'         => $ville->getId(),
                    'deleteLink' => $this->get('translator')->trans('table.action.delete'),
                    'editLink'   => $this->get('translator')->trans('table.action.edit'),
                    'closeText'  => $this->get('translator')->trans('modal.close'),
                    'titleModal' => $this->get('translator')->trans('modal.confirmMessage'),
                    'yes'        => $this->get('translator')->trans('modal.yes'),
                    'no'         => $this->get('translator')->trans('modal.no')
                );
            }
            return new JsonResponse($msg);
        }
        // ---------- FIN AJAX ----------

        $listeVilles = $em->getRepository('AlexBundle:Ville')->findAll();

        return $this->render('AlexBundle:Ville:listeVille.html.twig', array(
            'listeVilles' => $listeVilles,
            'formNvVille' => $form->createView()
        ));
    }

    /**
     * @Route("/{_locale}/villes/delete/{id}", name="villes_delete")
     * @Template()
     */
    public function deleteVilleAction(Request $request, $id) {
        if($id) {
            if($request->isXmlHttpRequest()) {
                $em = $this->getDoctrine()->getEntityManager();
                $ville = $em->getRepository('AlexBundle:Ville')->find($id);
                $em->remove($ville);
                $em->flush();

                $msg = array(
                    'type'       => 'success',
                    'msg'        => $this->get('translator')->trans('ville.deletedSuccess')
                );
                return new JsonResponse($msg);
            }
        }
        return $this->redirectToRoute('villes_list');
    }

    /**
     * @Route("/{_locale}/villes/edit/{id}", name="villes_edit")
     * @Template()
     */
    public function editVilleAction(Request $request, $id) {

        if($id) {

        }
        $em = $this->getDoctrine()->getManager();
        $ville = $em->getRepository('AlexBundle:Ville')->find($id);

        $form = $this->createForm(VilleType::class, $ville);
        $form->add('send', SubmitType::class, ['label' => 'edit.btn']);
        $form->handleRequest($request);

        // ---------- SUBMIT FORM WITH AJAX ----------
        if($request->isXmlHttpRequest()) {
            $form = $request->get('ville');
            // dans var/logs/dev.log
            //$logger->error('FORM name : '.$form['name']);

            if(empty($form['nom'])) {
                $msg = array(
                    'type' => 'error',
                    'msg'  => $this->get('translator')->trans('ville.emptyName')
                );
            } else {
                $em->persist($ville);
                $em->flush();
                $msg = array(
                    'type'       => 'success',
                    'msg'        => $this->get('translator')->trans('ville.createdSuccess'),
                    'nom'        => $form['nom'],
                    'id'         => $ville->getId(),
                );
            }
            return new JsonResponse($msg);
        }
        // ---------- FIN AJAX ----------

        return $this->render('AlexBundle:Discipline:editVille.html.twig', array(
            'ville' => $ville,
            'formEditVille' => $form->createView()
        ));
    }
}
