<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Validator\Constraints\Date;

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

    /** Akcja pobierania i wyświetlania użytkowników dla user managment
     * @Route("/admin/userManagment/{page}", name="admin_managment")
     * @Template()
     */
    public function userManagmentAction($page)
    {
	    if(!$this->checkPermission(2)){
		    throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
	    }

	    //Obliczenie ilości wynikow na stronie
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

	    //amount of pages
	    $amount = $helper->getUsersAmount();
		$pages  = ceil($amount[0][1] / 10);

	    return array('page' => $page, 'users' => $users, 'pages' => $pages);
    }

    /** Akcja odpowiedzialna za wyświetlanie ip-ów z bazy
     * @Route("/admin/ipManagment/{page}", name="admin_ips")
     * @Template()
     */
    public function ipManagmentAction($page)
    {
	    if(!$this->checkPermission(2)){
		    throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
	    }

	    //Obliczenie ilości wynikow na stronie
	    $offset = ($page - 1) * 10;
	    $limit  = $page * 10;

	    /**
	     * @var $helper \Gitbox\Bundle\CoreBundle\Helper\IPHelper
	     */
	    $helper  = $this->container->get('ip_helper');
	    $results = $helper->getIPsByLimit($offset, $limit);


	    //amount of pages
	    $amount = $helper->getIPsAmount();
	    $pages  = ceil($amount[0][1] / 10);

	    $ipArray = array();
	    foreach($results as $result) {
			$ipSingle = array();
		    $ipSingle['id'] = $result->getId();
		    $ipSingle['ip'] = $result->getIp();
		    $ipSingle['createDate'] = $result->getCreateDate()->format('Y-m-d H:i:s');
		    $ipArray[] = $ipSingle;
	    }

	    return array('page' => $page, 'results' => $ipArray, 'pages' => $pages);
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

	/** Dodaj ip
	 * @Route("/admin/ipManagment/manage/add", name="ip_managment_add")
	 */
	public function addIP(Request $request) {
		if(!$this->checkPermission(2)){
			throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
		}
		$ip   = $request->query->get('ip');
		$date = (new \DateTime());

		if(!preg_match('#[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}#', $ip)) {
			$response = array('error' => 'Podaj poprawne ip.');
		}else {
			$ipObject = new \Gitbox\Bundle\CoreBundle\Entity\BannedIp();
			$ipObject->setCreateDate($date);
			$ipObject->setIp($ip);

			/**
			 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\IPHelper
			 */
			$helper   = $this->container->get('ip_helper');
			$ipObject = $helper->insert($ipObject);

			$ipSingle = array();
			$ipSingle['id'] = $ipObject->getId();
			$ipSingle['ip'] = $ipObject->getIp();
			$ipSingle['createDate'] = $ipObject->getCreateDate()->format('Y-m-d H:i:s');

			$response = array(
				'data' => $ipSingle,
				'url'  => $this->generateUrl('ip_managment_remove', array('id' => $ipSingle['id']))
			);
		}

		$response = json_encode($response);
		return new Response($response, 200, array('Content-Type'=>'application/json'));
	}

	/** Usuwa ip
	 * @Route("/admin/ipManagment/manage/remove/{id}", name="ip_managment_remove")
	 */
	public function removeIP($id) {
		if(!$this->checkPermission(2)){
			throw $this->createNotFoundException("Strona nie istnieje lub brak dostępu");
		}
		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\IPHelper
		 */
		$helper   = $this->container->get('ip_helper');
		$helper->remove((int)$id);

		return new Response('', 200, array('Content-Type'=>'application/json'));
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
