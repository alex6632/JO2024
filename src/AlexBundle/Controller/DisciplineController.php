<?php

namespace AlexBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;

/**
 * @Route("/alex")
 */
class DisciplineController extends Controller
{
    /**
     * @Route("/discipline", name="discipline")
     * @Template()
     */
    public function disciplineAction(Request $request) {

    }
}
