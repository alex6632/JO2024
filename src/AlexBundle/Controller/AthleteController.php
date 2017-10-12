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
     * @Route("/athletes", name="athletes_list")
     * @Template()
     */
    public function athleteAction(Request $request) {

        $em = $this->getDoctrine()->getEntityManager();

        $athlete = new Athlete();
        $form = $this->createForm(AthleteType::class, $athlete);
        $form->add('send', SubmitType::class, ['label' => 'Créer l\'athléte']);
        $form->handleRequest($request);

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

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Athlète crée !');
        }

        $listeAthletes = $em->getRepository('AlexBundle:Athlete')->findAll();

        return $this->render('AlexBundle:Athlete:listeAthlete.html.twig', array(
            'listeAthletes' => $listeAthletes,
            'formNvAthlete' => $form->createView()
        ));
    }

    /**
     * @Route("/athlete/delete/{id}", name="athlete_delete")
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
        $session->getFlashBag()->add('success', 'Athlète supprimé !');

        return $this->redirectToRoute('athletes_list');
    }

    /**
     * @Route("/athlete/edit/{id}", name="athlete_edit")
     * @Template()
     */
    public function editAthleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $athlete = $em->getRepository('AlexBundle:Athlete')->find($id);

        $form = $this->createForm(AthleteType::class, $athlete);
        $form->add('send', SubmitType::class, ['label' => 'Editer l\'athlète']);
        $form->handleRequest($request);

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

            $session = $this->get('session');
            $session->getFlashBag()->add('success', 'Athlète édité !');

            return $this->redirectToRoute('pays_list');
        } else {
            // On supprime l'ancien drapeau
            $athletePhoto = $athlete->getPhoto();
            $athletePath = $this->get('kernel')->getRootDir().'/../web/images/uploads/photos/'.$athletePhoto;
            unlink($athletePath);
        }

        return $this->render('AlexBundle:Athlete:editAthlete.html.twig', array(
            'athlete' => $athlete,
            'formEditAthlete' => $form->createView()
        ));
    }
}
