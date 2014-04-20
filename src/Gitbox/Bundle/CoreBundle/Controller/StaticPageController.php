<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaticPageController extends Controller
{
    /**
     * @Route("/features", name="features_url")
     * @Template()
     */
    public function featuresAction()
    {
        return array();
    }

    /**
     * @Route("/help", name="help_url")
     * @Template()
     */
    public function helpAction()
    {
        return array();
    }

    /**
     * @Route("/about", name="about_url")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

}
