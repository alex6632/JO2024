<?php

namespace AlexBundle\Controller;

use AlexBundle\Entity\Discipline;
use AlexBundle\Form\DisciplineType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/alex")
 */
class DisciplineController extends Controller
{
    /**
     * @Route("/{_locale}/disciplines", name="disciplines_list")
     * @Template()
     */
    public function disciplineAction(Request $request) {

        $em = $this->getDoctrine()->getEntityManager();

        $discipline = new Discipline();

        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->add('send', SubmitType::class, ['label' => 'discipline.form.create']);
        $form->handleRequest($request);
        $session = $this->get('session');

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($discipline);
            $em->flush();

            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('discipline.create.succes')
            );
        }
        if($form->isSubmitted() && !$form->isValid()){
            $session->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('create.error')
            );
        }

        $listeDiscipline = $em->getRepository('AlexBundle:Discipline')->findAll();

        return $this->render('AlexBundle:Discipline:listeDiscipline.html.twig', array(
            'listeDisciplines' => $listeDiscipline,
            'formNvDiscipline' => $form->createView()
        ));
    }

    /**
     * @Route("/{_locale}/discipline/delete/{id}", name="discipline_delete")
     * @Template()
     */
    public function deletePaysAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $discipline = $em->getRepository('AlexBundle:Discipline')->find($id);
        $em->remove($discipline);
        $em->flush();

        $session = $this->get('session');
        $session->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('discipline.delete.succes')
        );

        return $this->redirectToRoute('disciplines_list');
    }

    /**
     * @Route("/{_locale}/discipline/edit/{id}", name="discipline_edit")
     * @Template()
     */
    public function editPaysAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $discipline = $em->getRepository('AlexBundle:Discipline')->find($id);

        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->add('send', SubmitType::class, ['label' => 'edit.btn']);
        $form->handleRequest($request);
        $session = $this->get('session');

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($discipline);
            $em->flush();

            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('discipline.edit.succes')
            );

            return $this->redirectToRoute('disciplines_list');
        }
        if($form->isSubmitted() && !$form->isValid()){
            $session->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('edit.error')
            );
        }

        return $this->render('AlexBundle:Discipline:editDiscipline.html.twig', array(
            'discipline' => $discipline,
            'formEditDiscipline' => $form->createView()
        ));
    }
}
