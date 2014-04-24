<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogController extends Controller
{
    /**
     * @Route("/user/{login}/blog", name="user_blog")
     * @Template()
     */
    public function indexAction($login)
    {
        // TODO: pobieranie postów z bazy oraz paginacja [https://github.com/KnpLabs/KnpPaginatorBundle] + ilość komentarzy
        $user['login'] = $login;
        $posts = array(
            array(
                'id' => '1',
                'title' => 'Damy radę!',
                'description' => "4 ziomków (właściwie 3) robiło sobie projekt zespołowy, gdy nagle czas przyspieszył i była godzina 14:30, dnia 24.04.2014r.\nNiestety, nasi dzielni bohaterowie bardzo lubili placki i Kacper wybuchł. KONIEC!",
                'create_date' => date('D j, F Y - H:i:s'),
                'modification_date' => date('D j, F Y - H:i:s')
            ),
            array(
                'id' => '2',
                'title' => 'Zdążyliśmy, zdaliśmy!',
                'description' => "No i zdaliśmy!",
                'create_date' => date('D j, F Y - H:i:s'),
                'modification_date' => date('D j, F Y - H:i:s')
            ),
            array(
                'id' => '3',
                'title' => 'Zdążyliśmy, zdaliśmy!',
                'description' => "No i zdaliśmy!",
                'create_date' => date('D j, F Y - H:i:s'),
                'modification_date' => date('D j, F Y - H:i:s')
            ),
            array(
                'id' => '4',
                'title' => 'Zdążyliśmy, zdaliśmy!',
                'description' => "No i zdaliśmy!",
                'create_date' => date('D j, F Y - H:i:s'),
                'modification_date' => date('D j, F Y - H:i:s')
            ),
            array(
                'id' => '5',
                'title' => 'Zdążyliśmy, zdaliśmy!',
                'description' => "No i zdaliśmy!",
                'create_date' => date('D j, F Y - H:i:s'),
                'modification_date' => date('D j, F Y - H:i:s')
            ),
            array(
                'id' => '6',
                'title' => 'Zdążyliśmy, zdaliśmy!',
                'description' => "No i zdaliśmy!",
                'create_date' => date('D j, F Y - H:i:s'),
                'modification_date' => date('D j, F Y - H:i:s')
            )
        );

        return array('user' => $user, 'posts' => $posts);
    }

    /**
     * @Route("/user/{login}/blog/new", name="user_new_post")
     * @Template()
     */
    public function newAction($login)
    {
        $user['login'] = $login;

        return array('user' => $user);
    }

    /**
     * @Route("/user/{login}/blog/{id}/edit")
     * @Template()
     */
    public function editAction($login, $id)
    {
    }

    /**
     * @Route("/user/{login}/blog/{id}", name="user_show_post")
     * @Template()
     */
    public function showAction($login, $id)
    {
        // TODO: pobieranie treści posta i komentarzy
        $user['login'] = $login;
        $post = array(
            'id' => '1',
            'title' => 'Damy radę!',
            'description' => "4 ziomków (właściwie 3) robiło sobie projekt zespołowy, gdy nagle czas przyspieszył i była godzina 14:30, dnia 24.04.2014r.\nNiestety, nasi dzielni bohaterowie bardzo lubili placki i Kacper wybuchł. KONIEC!",
            'create_date' => date('D j, F Y - H:i:s'),
            'modification_date' => date('D j, F Y - H:i:s')
        );

        return array('user' => $user, 'post' => $post);
    }

}
