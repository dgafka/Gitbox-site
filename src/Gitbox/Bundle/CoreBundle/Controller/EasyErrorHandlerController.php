<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EasyErrorHandlerController extends Controller
{
    /**
     * @Route("/accountFailActivation")
     * @Template()
     */
    public function accountFailActivationAction()
    {
    }

    /**
     * @Route("/accountNotActive")
     * @Template()
     */
    public function accountNotActiveAction()
    {
    }

}
