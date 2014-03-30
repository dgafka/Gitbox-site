<?php

namespace Gitbox\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\ApplicationBundle\Entity\UserAccount;
use Gitbox\ApplicationBundle\Form\UserAccountType;

/**
 * Login controller.
 *
 * @Route("/login")
 */
class LoginController extends Controller
{

    /**
     * User login method.
     *
     * @Route("/login")
     */
    public function indexAction()
    {

    }
}
