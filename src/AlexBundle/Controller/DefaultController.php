<?php

namespace AlexBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;

/**
 * @Route("/alex")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template
     */
    public function indexAction()
    {
        //return $this->render('AlexBundle:index.html.twig');
        return $this->redirectToRoute('pays_list');
    }
}
