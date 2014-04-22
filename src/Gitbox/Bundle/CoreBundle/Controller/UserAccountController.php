<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserAccountController extends Controller
{
    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction()
    {
    }

    /**
     * @Route("/Register")
     * @Template()
     */
    public function RegisterAction()
    {
	    return array();
    }

    /**
     * @Route("/RegisterSubmit")
     * @Template()
     */
    public function RegisterSubmitAction()
    {
    }

    /**
     * @Route("/Login")
     * @Template()
     */
    public function LoginAction()
    {
    }

    /**
     * @Route("/LoginSubmit")
     * @Template()
     */
    public function LoginSubmitAction()
    {
    }

}
