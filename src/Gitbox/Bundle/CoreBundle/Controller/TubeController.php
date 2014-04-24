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

}
