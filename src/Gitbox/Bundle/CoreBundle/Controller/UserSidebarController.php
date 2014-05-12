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
        $actionUrl = $request->get('actionUrl');
        $userId = $user->getId();

        if (!isset($user) && $user instanceof UserAccount) {
            throw new Exception("Brak przekazanego parametru do akcji. Wymagany parametr typu UserAccount");
        }

        $userDescHelper = $this->container->get('user_description_helper');
        $userDescription = $userDescHelper->findByUserId($userId);

        $moduleHelper = $this->container->get('module_helper');
        $userModule = $moduleHelper->findUserModule($userId);

        // obsługa formularza
        $formTitle = $this->get('form.factory')->createNamedBuilder(null, 'form', array(), array('csrf_protection' => false))
            ->add('title', 'text', array(
                'label' => false,
                'attr' => array (
                    'class'       => 'form-control',
                    'placeholder' => 'Wyszukaj po tytule'
                ),
                'required' => true,
                'max_length' => 50,
                'trim' => true
            ))
            ->add('submit', 'submit', array(
                'attr' => array (
                    'class' => 'btn btn-default'
                )
            ))
            ->getForm();

        $formCategory = $this->get('form.factory')->createNamedBuilder(null, 'form', array(), array('csrf_protection' => false))
            ->add('category', 'entity', array(
                'empty_value' => 'Wybierz kategorię',
                'class' => 'GitboxCoreBundle:Category',
                'property' => 'name',
                'expanded'  => false,
                'multiple'  => true
            ))
            ->add('submit', 'submit', array(
                'attr' => array (
                    'class' => 'btn btn-default'
                )
            ))
            ->getForm();

        return array(
            'user' => $user,
            'userDescription' => $userDescription,
            'userModule' => $userModule,
            'formTitle' => $formTitle->createView(),
            'formCategory' => $formCategory->createView(),
            'actionUrl' => $actionUrl
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
