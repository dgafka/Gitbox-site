<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

class RatingController extends Controller
{
    /**
     * @Route("/user/{login}/{module}/{id}/{type}", name="user_content_vote")
     */
    public function voteAction($login, $module, $id, $type) {
        // inicjalizacja odpowiedzi serwera
        $response = new JsonResponse();

        // pobieranie żądania
        $request = $this->get('request');
        // pobieranie `ciasteczka`
        $cookieVote = $request->cookies->get('vote_' . $id);

        // sprawdzenie czy oddano głos na podany content
        if (isset($cookieVote)) {
            $response->setData(array(
                'success' => false,
                'msg' => 'Już wcześniej zagłosowałeś na ten artykuł!'
            ));

            return $response;
        }

        // pobranie id użytkownika z sesji
        $session = $this->container->get('session');
        $userId = $session->get('userId');

        // inicjalizacja helpera, wywołanie metody pomocniczej oraz przechwycenie zwrotnych danych
        $ratingHelper = $this->container->get('rating_helper');
        $result = $ratingHelper->doVote($id, $type, $userId);
        $response->setData($result);

        // ustawienie `ciasteczka` z wartością id content-u
        if ($result['success'] == true) {
            $cookie = new Cookie('vote_' . $id, $type, time() + 3600 * 24 * 365);
            $response->headers->setCookie($cookie);
            $response->sendHeaders(); // wyślij same nagłówki
        }

        return new JsonResponse($result);
    }

}
