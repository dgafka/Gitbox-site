<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Form\Type\DriveContenerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Gitbox\Bundle\CoreBundle\Form\Type\DriveElementType;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Symfony\Component\HttpFoundation\Session\Session;

class DriveController extends Controller
{


    /**
     * @param $login
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function validateURL($login) {
        $userHelper = $this->container->get('user_helper');
        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitDrive');

        $user = $userHelper->findByLogin($login);

        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>.');
        } else if (!$moduleHelper->isModuleActivated($login)) {
            throw $this->createNotFoundException('Użytkownik <b>' . $login . '</b> nie posiada aktywowanego modułu.');
        }

        return $user;
    }

    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function userCheckContent($menu){
        $userHelper = $this->container->get('user_helper');
        $drivePermissionHelper = $this->container->get('dp_helper');
        $username=$drivePermissionHelper->checkUser();
        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitDrive');

          if (!isset($username)){
            throw $this->createNotFoundException('Zaloguj się, aby mieć dostęp do tej aktywności.');
        }
        if (!$moduleHelper->isModuleActivated($username)) {
            throw $this->createNotFoundException('Ten moduł nie jest włączony na twoim koncie');
        }
        if($menu->getUserId()->getLogin()!= $username)
        {
            throw $this->createNotFoundException('Nie mozesz edytowac cudzych elementtow');
        }
        return $userHelper->findByLogin($username);
    }

    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getPageContent($userid, $element, $request){
        $contentHelper = $this->container->get('drive_content_helper');
        $pageContent = $contentHelper->getContent($element, $request);

        if (!isset($pageContent)){
            throw $this->createNotFoundException('Nie znaleziono elementu ');
        }
        if ($pageContent->getIdUser() != $userid) {
            throw $this->createNotFoundException('Nie znaleziono elementu dla uzytkownika');
        }
        return $pageContent;
    }

    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getMenuPageContent($userid, $element, $request){
        $contentHelper = $this->container->get('drive_content_helper');
        $pageContent = $contentHelper->getMenu(intval($element), $request);

        if (!isset($pageContent)){
            throw $this->createNotFoundException('Nie znaleziono elementu ');
        }
        if ($pageContent->getIdUser()->getId() != $userid) {
            throw $this->createNotFoundException('Nie znaleziono elementu dla uzytkownika');
        }
        return $pageContent;
    }


    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getMenuStructure($menuId, $request){
        $contentHelper = $this->container->get('drive_content_helper');
        $menus = $contentHelper->getMenus($menuId, $request);
        $menu_contents = $contentHelper->getMenuContent($menuId, $request);
        $struktura="<ul>";
        foreach($menus as $menuI)
        {
            $struktura=$struktura."<li><span class=\"glyphicon glyphicon-folder-close\"></span>".$menuI->getTitle();
            $struktura=$struktura.$this->getMenuStructure($menuI->getId(), $request)."</li>";
        }
        foreach($menu_contents as $menuI)
        {
            $struktura=$struktura."<li><a href=\"{{ path('drive_contener_new') }}\"><span class=\"glyphicon glyphicon-hdd\"></span>".$menuI->getTitle()."</a></li>";
        }
        $struktura=$struktura."</ul>";
        return $struktura;
    }


    /**
     * @Route("/drive")
     * @Template()
     */
    public function DriveStartAction()
    {

        $drivePermissionHelper = $this->container->get('dp_helper');
        $username = $drivePermissionHelper->checkUser();
        if(isset($username))
        {
            $response = $this->forward('GitboxCoreBundle:Drive:DriveIndex', array(
                'login'  => $username
            ));


            return $response;
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }

    }


    /**
     * @Route("/user/{login}/drive",name="drive_user_index")
     * @Template()
     */
    public function DriveIndexAction($login)
    {
        $user = $this -> validateURL($login);

        $contentHelper = $this->container->get('drive_content_helper');
        $permissionHelper = $this->container->get('permissions_helper');
        $request = $this->get('request');
        $menuRoot= $contentHelper->getMenuZero($login, $request);
        $menuId = $menuRoot->getId();
        $menus = $contentHelper->getMenus($menuId, $request);
        $menu_contents = $contentHelper->getMenuContent($menuId, $request);


        $logged = $permissionHelper -> checkPermission($login);
        $countMenus = $contentHelper -> countMenus($user->getId(),$request);
        $countMenus--;
        $countAttachments = $contentHelper -> countAttachments($user->getId(),$request);


        $pageContent = $menuRoot;
        $menuCon = $contentHelper ->getMenus($pageContent->getId(), $request);
        $conCon = $contentHelper ->getMenuContent($pageContent->getId(), $request);

        return array(
            'user' => $user,
            'menus' => $menus,
            'contents' => $menu_contents,
            'logged' => $logged,
            'counter' => $countMenus,
            'countatt' => $countAttachments,
            'pageContent' => $pageContent,
            'menuCon' => $menuCon,
            'conCon' => $conCon
        );
    }

    /**
     * @Route("/user/{login}/drive/menu/{element}",name="drive_show_menu")
     * @Template()
     */
    public function DriveShowMenuAction($login, $element)
    {
        $user = $this -> validateURL($login);

        $contentHelper = $this->container->get('drive_content_helper');
        $permissionHelper = $this->container->get('permissions_helper');
        $request = $this->get('request');
        $menuRoot= $contentHelper->getMenuZero($login, $request);
        $menuId = $menuRoot->getId();
        $menus = $contentHelper->getMenus($menuId, $request);
        $menu_contents = $contentHelper->getMenuContent($menuId, $request);

        $logged = $permissionHelper -> checkPermission($login);
        $countMenus = $contentHelper -> countMenus($user->getId(),$request);
        $countMenus--;
        $countAttachments = $contentHelper -> countAttachments($user->getId(),$request);


        $pageContent = $this ->getMenuPageContent($user->getId(), $element, $request);
        if($pageContent -> getParent() == null){
            return $this->redirect($this->generateUrl('drive_user_index', array(
                'login'=>$login ),true));
        }
        $menuCon = $contentHelper ->getMenus($pageContent->getId(), $request);
        $conCon = $contentHelper ->getMenuContent($pageContent->getId(), $request);

        return array(
            'user' => $user,
            'menus' => $menus,
            'contents' => $menu_contents,
            'logged' => $logged,
            'counter' => $countMenus,
            'countatt' => $countAttachments,
            'pageContent' => $pageContent,
            'menuCon' => $menuCon,
            'conCon' => $conCon
        );

    }

    /**
     * @Route("user/{login}/drive/menu/{element}/new/contener",name="drive_contener_new")
     * @Template()
     */
    public function NewDriveContenerAction($login, $element)
    {
        $user = $this -> validateURL($login);
        $request = $this->get('request');
        $this ->getMenuPageContent($user->getId(), $element, $request);

        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitDrive');
        $modulee = $moduleHelper -> findModule();


        $newMenu = new Menu();
        $newMenu->setIdUser($user);
        $newMenu->setParent($element);
        $newMenu->setIdModule($modulee);

        $form = $this->createForm(new DriveContenerType(), $newMenu);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $menuHelper = $this->container->get('menu_helper');
            $menuHelper -> insert($newMenu);
            ;

            return $this->redirect($this->generateUrl('drive_show_menu', array(
                'login'=>$login,
            'element' => $element),true));
        }

        return array(
            'user' => $user,
            'form' => $form->createView()

        );


    }


    /**
     * @Route("user/{login}/drive/menu/{element}/edit",name="drive_contener_edit")
     * @Template("GitboxCoreBundle:Drive:NewDriveContener.html.twig")
     */
    public function EditDriveContenerAction($login, $element)
    {
        $user = $this -> validateURL($login);
        $request = $this->get('request');
        $oldMenu=$this ->getMenuPageContent($user->getId(), $element, $request);


        $form = $this->createForm(new DriveContenerType(), $oldMenu);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $menuHelper = $this->container->get('menu_helper');
            $menuHelper -> update($oldMenu);


            return $this->redirect($this->generateUrl('drive_show_menu', array(
                'login'=>$login,
                'element' => $element),true));
        }

        return array(
            'user' => $user,
            'form' => $form->createView()

        );


    }

    /**
     * @Route("user/{login}/drive/menu/{element}/remove",name="drive_contener_edit")

     */
    public function RemoveDriveContenerAction($login, $element)
    {
        $user = $this -> validateURL($login);
        $request = $this->get('request');
        $oldMenu=$this ->getMenuPageContent($user->getId(), $element, $request);
        $parent = $oldMenu -> getParent();


        if($parent !=null)
        {
            $menuHelper = $this->container->get('menu_helper');
            $menuHelper -> remove($oldMenu->getId());


            return $this->redirect($this->generateUrl('drive_show_menu', array(
                'login'=>$login,
                'element' => $parent),true));
        }
        else
        {
            return $this->redirect($this->generateUrl('drive_user_index', array(
                'login'=>$login),true));
        }


    }


    /**
     * @Route("/new/drive",name="drive_item_new")
     * @Template()
     */
    public function NewDriveItemAction()
    {

        $user=$this->userCheckContent();
	$form = $this->createForm(new DriveElementType());
	 return array(
         'user' => $user,
		'form' => $form->createView()
	);
    }









    /**
     * @Route("/user/{login}/drive/content/{element}",name="drive_show_content")
     * @Template()
     */
    public function DriveShowAction($login, $element)
    {
        $contentHelper = $this->container->get('drive_content_helper');
        $permissionHelper = $this->container->get('permissions_helper');
        $request = $this->get('request');
        $menuRoot= $contentHelper->getMenuZero($login, $request);
        $menuId = $menuRoot->getId();
        $menus = $contentHelper->getMenus($menuId, $request);
        $menu_contents = $contentHelper->getMenuContent($menuId, $request);

        $userHelper = $this->container->get('user_helper');
        $user = $userHelper->findByLogin($login);
        $logged = $permissionHelper -> checkPermission($login);
        $countMenus = $contentHelper -> countMenus($user->getId(),$request);
        $countMenus--;
        $countAttachments = $contentHelper -> countAttachments($user->getId(),$request);


        $pageContent = $this ->getPageContent($user->getId(), $element, $request);
        $page_attachments = $contentHelper->getAttachments($element, $request);


        $structure = $this ->  getMenuStructure($menuRoot, $request);

        return array(
            'user' => $user,
            'menus' => $menus,
            'contents' => $menu_contents,
            'pageContent' => $pageContent,
            'pageContentAttachments' => $page_attachments,
            'logged' => $logged,
            'abc'=> $structure,
            'counter' => $countMenus,
            'countatt' => $countAttachments
        );
    }

    /**
     * @Route("/edit/drive/{element}")
     * @Template()
     */
    public function DriveEditAction($element)
    {


        $user=$this->userCheckContent();
        $form = $this->createForm(new DriveElementType());
        return array(
            'form' => $form->createView(),
            'user' => $user

        );
    }

    /**
     * @Route("/edit/drive/contener/{element}")
     * @Template("GitboxCoreBundle:Drive:NewDriveContener.html.twig")
     */
    public function DriveEditContenerAction($element)
    {


        $user=$this->userCheckContent();
        $form = $this->createForm(new DriveElementType());
        return array(
            'form' => $form->createView(),
            'user' => $user

        );
    }

}
