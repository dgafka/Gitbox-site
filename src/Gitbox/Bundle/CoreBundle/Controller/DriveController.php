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
    private function userCheckContent(){
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
        $pageContent = $contentHelper->getMenu($element, $request);

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
     * @Route("/user/{login}/drive")
     * @Template()
     */
    public function DriveAction($login)
    {

        // walidacja dostępu
        $user = $this->validateURL($login);

     return array(
        'user' => $user
    );
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
     * @Route("new/drive/contener",name="drive_contener_new")
     * @Template()
     */
    public function NewDriveContenerAction()
    {
        $user=$this->userCheckContent();

        $xContent = new Content();
        $form = $this->createForm(new DriveContenerType());
        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }


    /**
     * @Route("/user/{login}/drive/menu/{element}",name="drive_show_menu")
     * @Template()
     */
    public function DriveShowMenuAction($login, $element)
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


        $pageContent = $this ->getMenuPageContent($user->getId(), $element, $request);
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
