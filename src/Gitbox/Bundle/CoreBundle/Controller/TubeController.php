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
        $user = $this->getUserByLogin($login);
        $posts = array(
            array(
                'id' => '1',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '2',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '3',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '4',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            ),
            array(
                'id' => '5',
                'title' => 'Template preview',
                'header' => 'http://4.bp.blogspot.com/-zlr_-dVCduI/T9nvwQN3v9I/AAAAAAAAAcc/0BG1BV09XcQ/s640/video_preview.jpg'
            )
        );
        return array('user' => $user, 'posts' => $posts);
    }
    private function getUserByLogin($login) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('login' => $login));
        if(!$user instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
            throw $this->createNotFoundException("Nie znaleziono podanego użytkownika");
        }

        return $user;
    }

}
