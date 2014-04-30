<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DriveController extends Controller
{
    /**
     * @Route("/user/{login}/drive/new")
     * @Template()
     */
    public function NewDriveItemAction($login)
    {
    }

}
