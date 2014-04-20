<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LoginFormController extends Controller
{
    /**
     * @Template()
     */
    public function renderAction()
    {
        // tests
        $userSession = true;

        // return values depending of user status (login/logout)
        return array('user_session' => $userSession);
    }

}
