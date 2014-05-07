<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gitbox\Bundle\CoreBundle\Entity\UserAccount;

class UserSidebarController extends Controller
{
    /**
     * @Template()
     */
    public function renderAction() {
        $request = $this->get('request');

        $user = $request->get('user');
        $userId = $user->getId();

        if (!isset($user) && $user instanceof UserAccount) {
            throw new Exception("Brak przekazanego parametru do akcji. Wymagany parametr typu UserAccount");
        }

        $userDescHelper = $this->container->get('user_description_helper');
        $userDescription = $userDescHelper->findByUserId($userId);

        $moduleHelper = $this->container->get('module_helper');
        $userModule = $moduleHelper->findUserModule($userId);

        return array(
            'user' => $user,
            'userDescription' => $userDescription,
            'userModule' => $userModule
        );
    }

    /**
     * @Route("/user/{login}/sidebar/update", name="user_sidebar_update")
     */
    public function updateAction($login) {
        // pobieranie żądania
        $request = $this->get('request');
        // inicjalizacja odpowiedzi serwera
        $response = new JsonResponse();

        // pobranie oceny i sumy głosów użytkownika
        $userDescHelper = $this->container->get('user_description_helper');
        $userDescription = $userDescHelper->findByLogin($login);

        $response->setData(array(
            'ratingScore' => $userDescription->getRatingScore(),
            'ratingQuantity' => $userDescription->getRatingQuantity()
        ));

        return $response;
    }

}
