<?php

namespace AlexBundle\Controller;

use AlexBundle\Entity\Athlete;
use AlexBundle\Form\AthleteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/alex")
 */
class AthleteController extends Controller
{
    /**
     * @Route("/{_locale}/athletes", name="athletes_list")
     * @Template()
     */
    public function athleteAction(Request $request) {

        $em = $this->getDoctrine()->getEntityManager();

        $athlete = new Athlete();
        $form = $this->createForm(AthleteType::class, $athlete);
        $form->add('send', SubmitType::class, ['label' => 'athlete.form.create']);
        $form->handleRequest($request);
        $session = $this->get('session');

        if($form->isSubmitted() && $form->isValid()) {
            // Upload...
            $file = $athlete->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_photo'),
                $fileName
            );
            $athlete->setPhoto($fileName);

            $em->persist($athlete);
            $em->flush();

            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('athlete.edit.succes')
            );
        }
        if($form->isSubmitted() && !$form->isValid()){
            $session->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('create.error')
            );
        }

        $disciplines = $em->getRepository('AlexBundle:Discipline')->findAll();
        $listeAthletes = $em->getRepository('AlexBundle:Athlete')->findAll();

        return $this->render('AlexBundle:Athlete:listeAthlete.html.twig', array(
            'listeAthletes' => $listeAthletes,
            'disciplines' => $disciplines,
            'formNvAthlete' => $form->createView()
        ));
    }

    /**
     * @Route("/{_locale}/athlete/delete/{id}", name="athlete_delete")
     * @Template()
     */
    public function deleteAthleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $athlete = $em->getRepository('AlexBundle:Athlete')->find($id);

        $athletePhoto = $athlete->getPhoto();
        $athletePath = $this->get('kernel')->getRootDir().'/../web/images/uploads/photos/'.$athletePhoto;
        unlink($athletePath);

        $em->remove($athlete);
        $em->flush();

        $session = $this->get('session');
        $session->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('athlete.delete.succes')
        );

        return $this->redirectToRoute('athletes_list');
    }

    /**
     * @Route("/{_locale}/athlete/edit/{id}", name="athlete_edit")
     * @Template()
     */
    public function editAthleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $athlete = $em->getRepository('AlexBundle:Athlete')->find($id);

        $form = $this->createForm(AthleteType::class, $athlete);
        $form->add('send', SubmitType::class, ['label' => 'edit.btn']);
        $form->handleRequest($request);
        $session = $this->get('session');

        if($form->isSubmitted() && $form->isValid()) {

            $file = $athlete->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_photo'),
                $fileName
            );
            $athlete->setPhoto($fileName);

            $em->persist($athlete);
            $em->flush();

            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('athlete.edit.succes')
            );

            return $this->redirectToRoute('athletes_list');
        } else {
            // On supprime l'ancien drapeau
            $athletePhoto = $athlete->getPhoto();
            $athletePath = $this->get('kernel')->getRootDir().'/../web/images/uploads/photos/'.$athletePhoto;
            unlink($athletePath);
        }
        if($form->isSubmitted() && !$form->isValid()){
            $session->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('edit.error')
            );
        }

        return $this->render('AlexBundle:Athlete:editAthlete.html.twig', array(
            'athlete' => $athlete,
            'formEditAthlete' => $form->createView()
        ));
    }
}
