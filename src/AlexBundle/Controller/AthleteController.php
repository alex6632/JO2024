<?php

namespace AlexBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;

/**
 * @Route("/alex")
 */
class AthleteController extends Controller
{
    /**
     * @Route("/athlete", name="athletes_list")
     * @Template()
     */
    public function athleteAction(Request $request) {

    }
}
