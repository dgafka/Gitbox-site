<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;

class AdminController extends Controller
{

	/** Główny panel administratora
	 * @Route("/admin", name="admin_panel")
	 * @Template()
	 */
	public function adminAction()
	{
		if(!$this->checkPermission(2)){
			throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
		}

		return array();
	}

    /**
     * @Route("/admin/userManagment/{page}", name="admin_managment")
     * @Template()
     */
    public function userManagmentAction($page)
    {
	    if(!$this->checkPermission(2)){
		    throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
	    }

		$offset = ($page - 1) * 10;
	    $limit  = $page * 10;

	    /**
	     * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
	     */
	    $helper  = $this->container->get('user_helper');
		$results = $helper->getUsersByLimit($offset, $limit);

	    $users   = array();

	    foreach($results as $result) {
		    $user = $helper->getUserDetails($result);
		    $users[] = $user;
	    }

	    $amount = $helper->getUsersAmount();
		$pages  = ceil($amount[0][1] / 10);

	    return array('page' => $page, 'users' => $users, 'pages' => $pages);
    }

    /**
     * @Template()
     */
    public function ipManagmentAction($page)
    {
	    if(!$this->checkPermission(2)){
		    throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
	    }

	    return array();
    }

    /**
     * @Template()
     */
    public function mainSiteManagmentAction()
    {
	    if(!$this->checkPermission(2)){
		    throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
	    }

	    return array();
    }

	/**
	 * Zmienia status użytkownika
	 * @param $id
	 * @param $status
	 * @Route("/admin/userManagment/change_status/{id}/{status}", name="admin_managment_status")
	 * @return Response
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function changeStatusAction($id, $status) {
		if(!$this->checkPermission(2)){
			throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
		}

		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		 */
		$helper  = $this->container->get('user_helper');
		$helper->changeStatus($id, $status);


		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		 */
		$helper      = $this->container->get('user_helper');
		$user        = $helper->getUserDetails((int)$id);

		$uniqueId    = uniqid(md5($id));
		$viewHtml    = $this->render('GitboxCoreBundle:Admin:singleUserManagment.html.twig', array('user' => $user, 'uniqueId' => $uniqueId));

		$responseArray = array('answer' => 'Status został zmieniony. Odświerz stronę, aby zobaczyć zmiany.', 'content' => $viewHtml->getContent());
		$responseArray = json_encode($responseArray);
		return new Response($responseArray, 200, array('Content-Type'=>'application/json'));

	}

	/** Zmienia poziom administratora
	 * @param $id
	 * @param $permission
	 * @Route("/admin/userManagment/change_permission/{id}/{permission}", name="admin_managment_permission")
	 * @return Response
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function changePermissionAction($id, $permission) {
		if(!$this->checkPermission(2)){
			throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
		}

		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		 */
		$helper  = $this->container->get('user_helper');
		$helper->changePermission($id, $permission);
		$user        = $helper->getUserDetails((int)$id);

		$uniqueId    = uniqid(md5($id));
		$viewHtml    = $this->render('GitboxCoreBundle:Admin:singleUserManagment.html.twig', array('user' => $user, 'uniqueId' => $uniqueId));

		$responseArray = array('answer' => 'Dostęp został zmieniony. Odświerz stronę, aby zobaczyć zmiany.', 'content' => $viewHtml->getContent());
		$responseArray = json_encode($responseArray);
		return new Response($responseArray, 200, array('Content-Type'=>'application/json'));

	}

	/** usuwa użytkownka
	 * @param $id
	 * @return Response
	 * @Route("/admin/userManagment/change_delete/{id}", name="admin_managment_delete")
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function deleteUser($id) {
		if(!$this->checkPermission(2)){
			throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
		}

		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		 */
		$helper  = $this->container->get('user_helper');
		$helper->remove($id);

		$responseArray = array('answer' => 'Użytkownik został usunięty. Odświerz stronę, aby zobaczyć zmiany.');
		$responseArray = json_encode($responseArray);
		return new Response($responseArray, 200, array('Content-Type'=>'application/json'));

	}

	/** Sprawdza dostęp do widoku
	 * @param $level
	 * @return bool
	 */
	private function checkPermission($level) {
		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\PermissionsHelper
		 */
		$helper = $this->container->get('permissions_helper');

		//sprawdzenie, czy użytkownik jest adminem
		if($level == 2) {
			return $helper->isAdmin();
		}
		if($level == 3) {
			return $helper->isMainAdmin();
		}

		return false;
	}
}
