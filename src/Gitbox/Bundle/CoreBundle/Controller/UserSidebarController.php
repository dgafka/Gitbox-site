<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
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

        // TODO: pass $moduleName to search for module stats like `quantity of posts`

        return array(
            'user' => $user,
            'userDescription' => $userDescription,
            'userModule' => $userModule
        );
    }

}
