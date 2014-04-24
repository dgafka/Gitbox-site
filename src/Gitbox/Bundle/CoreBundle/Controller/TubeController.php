<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TubeController extends Controller
{
    /**
     * @Route("/user/{login}/tube")
     * @Template()
     */
    public function indexAction($login)
    {
        $user['login'] = $login;
        $posts = array(
            array(
                'id' => '1',
                'title' => 'Template preview'
            ),
            array(
                'id' => '2',
                'title' => 'Template preview'
            )
        );
        return array('user' => $user, 'posts' => $posts);
    }

}
