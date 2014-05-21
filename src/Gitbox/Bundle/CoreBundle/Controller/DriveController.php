<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Form\Type\DriveAttachmentType;
use Gitbox\Bundle\CoreBundle\Form\Type\DriveContenerType;
use Proxies\__CG__\Gitbox\Bundle\CoreBundle\Entity\Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Gitbox\Bundle\CoreBundle\Form\Type\DriveElementType;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Symfony\Component\HttpFoundation\Session\Session;
use Gitbox\Bundle\MenuBundle\MenuTree;

class DriveController extends Controller
{
    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function menuPath($menux, $request){
        $path= array();
        $parent = $menux->getParent();
        if($parent !=null)
        {
            $path=$this-> menuPathX($parent, $path, $request);
            array_push($path,$menux);
        }
        return $path;
    }


    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function menuPathX($menuxId, $path, $request){
        $contentHelper = $this->container->get('drive_content_helper');
        $menux = $contentHelper->getMenu(intval($menuxId), $request);
        $parent = $menux->getParent();
        if($parent  !=null)
        {
            $path=$this-> menuPathX($parent, $path, $request);
            array_push($path,$menux);
        }
        return $path;
    }


    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function  validateUser(){
        $userHelper = $this->container->get('user_helper');
        $drivePermissionHelper = $this->container->get('dp_helper');

        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitDrive');

        $username = $drivePermissionHelper->checkUser();
        if(isset($username))
        {
            $user = $userHelper->findByLogin($username);
            if (!$moduleHelper->isModuleActivated($username)) {
                throw $this->createNotFoundException('Ten moduł nie jest aktywowany !');
            }
            return $user;
        }
        else return null;

        }



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
    private function userCheckContentX($menu){
        $userHelper = $this->container->get('user_helper');
        $user=$userHelper->find($menu->getIdUser());
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
        if($user->getLogin()!= $username)
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
        $pageContent = $contentHelper->getContent(intval($element), $request);

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
    private function getMenuStructure($menuId, $login, $request){
        $contentHelper = $this->container->get('drive_content_helper');
        $menuhelper = $this -> container->get('menu_helper');
        $menux= $menuhelper -> find($menuId);
        $menus = $contentHelper->getMenus($menuId, $request);
        $menu_contents = $contentHelper->getMenuContent($menuId, $request);
        $menuTree= new MenuTree();
        $menuTree -> setId($menuId);
        $menuTree -> setTitle($menux->getTitle());
        $menuTree ->setUser($login);
        $menuTreeMenus = array();
        foreach($menus as $menuI)
        {


            $menuTreeI= $this ->getMenuStructure($menuI->getId(),$login,$request);
            array_push($menuTreeMenus, $menuTreeI);

        }
        $menuTree->setMenus($menuTreeMenus);
        $menuTree ->setContents($menu_contents);
        return $menuTree;
    }


    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getMenuStructureX($login, $request){

        $contentHelper = $this->container->get('drive_content_helper');

        // menu
        $menuRoot= $contentHelper->getMenuZero($login, $request);
        if(!isset($menuRoot))
        {
            throw $this->createNotFoundException('Bład: Nie znaleziono tresci ');
        }
        else
        {
            $menuId = $menuRoot->getId();
            $menuTree = $this -> getMenuStructure($menuId, $login, $request);
        }

        return $menuTree;
    }



    /**
     * usuwa plik z folderu /web/tdrive/{userId}/{filename}
     * @param $userId
     * @param $fileName
     * @return bool
     */
    private function removeFile($userId, $fileName) {
        $path = $this->getUploadsDir() . 'drive' . $this->getDiviner() . $userId . $this->getDiviner() . $fileName;
        return @unlink($path);
    }

    private function getDiviner() {
        return '/';
    }

    /**
     * Zwraca sciezke dostepu do uploadu
     */
    private function getUploadsDir() {
        return __DIR__ . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . 'web' . $this->getDiviner() . 'uploads' . $this->getDiviner();
    }


    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */

    private function  removeMenuX($oldMenu, $request, $user){
        $menuHelper = $this->container->get('menu_helper');
        $contentHelper = $this->container->get('drive_content_helper');
        $menuCon = $contentHelper ->getMenus($oldMenu->getId(), $request);
        $conCon = $contentHelper ->getMenuContent($oldMenu->getId(), $request);

        if(isset($menuCon)){

               foreach($menuCon as $menux)
               {
                   $this->removeMenuX($menux, $request, $user);

               }
                foreach($conCon as $contentx)
                {
                    $this -> removeContentx($contentx, $request, $user);
                }


        }
        $menuHelper -> remove($oldMenu->getId());
    }

    private function removeContentx($element,$request, $user){
        $contentHelper = $this->container->get('drive_content_helper');
        $page_attachments = $contentHelper->getAttachments($element, $request);
        foreach($page_attachments as $att)
        {
            removeAttx($user, $att->getId());
        }

        $contentHelper -> remove($element->getId());

    }


    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function removeAttx($user, $el2)
    {
        $contentHelper = $this->container->get('drive_content_helper');
        $this->removeFile($user->getId(), $el2->getFilename());
        $contentHelper -> attRemove(intval($el2->getId()));

    }




    /**
     * @Route("/drive",name="drive_start")
     * @Template()
     */
    public function DriveStartAction()
    {
        $user = $this -> validateUser();

        if(isset($user))
        {
            $response = $this->forward('GitboxCoreBundle:Drive:DriveIndex', array(
                'login'  => $user->getLogin()
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
        $user = $this -> validateUser();

        if(isset($user))
        {
           if($user-> getLogin() == $login)
           {
               $request = $this->get('request');

               //helpery

               $contentHelper = $this->container->get('drive_content_helper');
               $permissionHelper = $this->container->get('permissions_helper');

               // menu
               $menuRoot= $contentHelper->getMenuZero($login, $request);
               $menuId = $menuRoot->getId();
               $menuTree = $this -> getMenuStructureX($login, $request);
               $menu_contents = $contentHelper->getMenuContent($menuId, $request);


               //pobranie i sprwdzenie zawartosci menu
               $pageContent = $menuRoot;
               $menuCon = $contentHelper ->getMenus($pageContent->getId(), $request);
               $conCon = $contentHelper ->getMenuContent($pageContent->getId(), $request);

               $path= array();
               array_push($path, $pageContent);

               //zalogowany? (ew. do usuniecia)
               $logged = $permissionHelper -> checkPermission($login);


               //zmienne dla paska uzytkownika
               $countMenus = $contentHelper -> countMenus($user->getId(),$request);
               $countMenus--;
               $countAttachments = $contentHelper -> countAttachments($user->getId(),$request);




               return array(
                   'user' => $user,
                   'contents' => $menu_contents,
                   'logged' => $logged,
                   'counter' => $countMenus,
                   'countatt' => $countAttachments,
                   'pageContent' => $pageContent,
                   'menuCon' => $menuCon,
                   'conCon' => $conCon,
                   'menuStructure' =>$menuTree,
                   'path' => $path
               );
           }
           else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }



    }

    /**
     * @Route("/user/{login}/drive/menu/{element}",name="drive_show_menu")
     * @Template()
     */
    public function DriveShowMenuAction($login, $element)
    {
        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {
                $request = $this->get('request');

                //helpery

                $contentHelper = $this->container->get('drive_content_helper');
                $permissionHelper = $this->container->get('permissions_helper');

                // menu
                $menuRoot= $contentHelper->getMenuZero($login, $request);
                $menuId = $menuRoot->getId();
                $menuTree = $this -> getMenuStructureX($login, $request);
                $menu_contents = $contentHelper->getMenuContent($menuId, $request);




                //pobranie i sprwdzenie zawartosci menu

               $pageContent = $this ->getMenuPageContent($user->getId(), $element, $request);
                if($pageContent -> getParent() == null){
                    return $this->redirect($this->generateUrl('drive_user_index', array(
                        'login'=>$login ),true));
                }
                $parentMenu = $pageContent->getParent();
                $menuCon = $contentHelper ->getMenus($pageContent->getId(), $request);
                $conCon = $contentHelper ->getMenuContent($pageContent->getId(), $request);

                $path = $this ->menuPath($pageContent, $request);



                //zalogowany? (ew. do usuniecia)
                $logged = $permissionHelper -> checkPermission($login);


                //zmienne dla paska uzytkownika
                $countMenus = $contentHelper -> countMenus($user->getId(),$request);
                $countMenus--;
                $countAttachments = $contentHelper -> countAttachments($user->getId(),$request);




                return array(
                    'user' => $user,
                    'contents' => $menu_contents,
                    'logged' => $logged,
                    'counter' => $countMenus,
                    'countatt' => $countAttachments,
                    'pageContent' => $pageContent,
                    'menuCon' => $menuCon,
                    'conCon' => $conCon,
                    'menuStructure' =>$menuTree,
                    'parentMenu' => $parentMenu,
                    'path' => $path
                );
            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }


    }

    /**
     * @Route("user/{login}/drive/menu/{element}/new/contener",name="drive_contener_new")
     * @Template()
     */
    public function NewDriveContenerAction($login, $element)
    {

        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {


                $request = $this->get('request');

                //helpery

                $moduleHelper = $this->container->get('module_helper');
                $moduleHelper->init('GitDrive');


                //pobieranie danych i sprawdzenia
                $modulee = $moduleHelper -> findModule();
                $pageContent = $this ->getMenuPageContent($user->getId(), $element, $request);

                $newMenu = new Menu();
                $newMenu->setIdUser($user);
                $newMenu->setParent($element);
                $newMenu->setIdModule($modulee);

                // inicjalizacja flash baga
                $session = $this->container->get('session');


                $form = $this->createForm(new DriveContenerType(), $newMenu);
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $menuHelper = $this->container->get('menu_helper');
                    $menuHelper -> insert($newMenu);


                    $session->getFlashBag()->add('success', 'Dodano kontener: ' . $newMenu->getTitle() );
                    return $this->redirect($this->generateUrl('drive_show_menu', array(
                        'login'=>$login,
                        'element' => $element),true));
                }

                return array(
                    'user' => $user,
                    'form' => $form->createView(),
                    'tytul' => "Nowy kontener",
                    'btnLabel' => 'dodaj',
                    'parentMenu' => $pageContent
                );



            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }







    }


    /**
     * @Route("user/{login}/drive/menu/{element}/edit",name="drive_contener_edit")
     * @Template("GitboxCoreBundle:Drive:NewDriveContener.html.twig")
     */
    public function EditDriveContenerAction($login, $element)
    {

        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {


                $request = $this->get('request');

                //helpery
                $moduleHelper = $this->container->get('module_helper');
                $moduleHelper->init('GitDrive');


                //pobieranie danych i sprawdzenia
                $oldMenu=$this ->getMenuPageContent($user->getId(), $element, $request);
                $parentMenu = $oldMenu;

                // inicjalizacja flash baga
                $session = $this->container->get('session');


                //formularz
                $form = $this->createForm(new DriveContenerType(), $oldMenu);
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $menuHelper = $this->container->get('menu_helper');
                    $menuHelper -> update($oldMenu);

                    $session->getFlashBag()->add('success', 'Zmieniono nazwę na: ' . $oldMenu->getTitle() );

                    return $this->redirect($this->generateUrl('drive_show_menu', array(
                        'login'=>$login,
                        'element' => $element),true));
                }

                return array(
                    'user' => $user,
                    'form' => $form->createView(),
                    'tytul' => "Edytuj kontener",
                    'btnLabel' => 'edytuj',
                    'parentMenu' => $parentMenu

                );



            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }







    }

    /**
     * @Route("user/{login}/drive/menu/{element}/remove",name="drive_contener_remove")

     */
    public function RemoveDriveContenerAction($login, $element)
    {
        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {


                $request = $this->get('request');

                //helpery
                $moduleHelper = $this->container->get('module_helper');
                $moduleHelper->init('GitDrive');


                $oldMenu=$this ->getMenuPageContent($user->getId(), $element, $request);
                $parent = $oldMenu -> getParent();

                // inicjalizacja flash baga
                $session = $this->container->get('session');


                if($parent !=null)
                {
                   $this -> removeMenuX($oldMenu, $request, $user);

                    $session->getFlashBag()->add('success', 'Usunięto: ' . $oldMenu->getTitle() );

                   return $this->redirect($this->generateUrl('drive_show_menu', array(
                       'login'=>$login,
                       'element' => $parent),true));


                }
                else
                {
                    $session->getFlashBag()->add('warning', 'Operacja niedozwolona' );
                    return $this->redirect($this->generateUrl('drive_user_index', array(
                        'login'=>$login),true));
                }



            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }



    }


    /**
     * @Route("/user/{login}/drive/content/{element}",name="drive_show_content")
     * @Template()
     */
    public function DriveShowAction($login, $element)
    {

        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {
                $request = $this->get('request');

                //helpery

                $contentHelper = $this->container->get('drive_content_helper');
                $permissionHelper = $this->container->get('permissions_helper');

                // menu boczne
                $menuRoot= $contentHelper->getMenuZero($login, $request);
                $menuId = $menuRoot->getId();
                $menuTree = $this -> getMenuStructureX($login, $request);
                $menu_contents = $contentHelper->getMenuContent($menuId, $request);


                //pobieranie tresci strony
                $newAttachment = new Attachment();

                $form = $this->createForm(new DriveAttachmentType(), $newAttachment);
                $form->handleRequest($request);

                $pageContent = $this ->getPageContent($user->getId(), $element, $request);
                $page_attachments = $contentHelper->getAttachments($element, $request);


                //pobieranie pliku

                $dir =  __DIR__.'/../../../../../web/uploads/drive/'.$user->getId().'/';


                // inicjalizacja flash baga
                $session = $this->container->get('session');

                if ($form->isValid()) {
                    $contentHelper = $this->container->get('drive_content_helper');
                    $em = $this->getDoctrine()->getManager();



                    $file = $form['filename']->getData();
                    $extension = $file->guessExtension();

                    if ($file->getClientSize() > 83886080) {
                        $session->getFlashBag()->add('warning', 'Rozmiar pliku musi być mniejszy niż 80Mb.');
                    }else{
                        $filename = uniqid();
                        $file->move($dir, $filename.'.'.$extension);

                        $newAttachment->setFilename($filename.'.'.$extension);
                        $newAttachment->setMime($extension);
                        $newAttachment ->setCreateDate(new \DateTime('now'));
                        $newAttachment->setIdContent($pageContent);
                        $contentHelper -> insertIntoAttachment($newAttachment);

                        $session->getFlashBag()->add('success', 'Dodano plik: ' . $newAttachment->getTitle() );
                        return $this->redirect($this->generateUrl('drive_show_content', array(
                            'login'=>$login,
                            'element' => $element),true));

                    }


                }


                $dirr = '../../../../../../../../../web/uploads/drive/'.$user->getId().'/';

                $parentMenu = $pageContent->getIdMenu();
                $path= $this ->menuPath($parentMenu, $request);



                //zalogowany? (ew. do usuniecia)
                $logged = $permissionHelper -> checkPermission($login);


                //zmienne dla paska uzytkownika
                $countMenus = $contentHelper -> countMenus($user->getId(),$request);
                $countMenus--;
                $countAttachments = $contentHelper -> countAttachments($user->getId(),$request);

                return array(
                    'user' => $user,
                    'contents' => $menu_contents,
                    'pageContent' => $pageContent,
                    'pageContentAttachments' => $page_attachments,
                    'logged' => $logged,
                    'counter' => $countMenus,
                    'countatt' => $countAttachments,
                    'menuStructure' => $menuTree,
                    'tytul' => '',
                    'form' => $form->createView(),
                    'dirr' => $dirr,
                    'btnLabel' => 'dodaj',
                    'parentMenu' =>$parentMenu,
                    'path' => $path
                );



            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }


        $contentHelper = $this->container->get('drive_content_helper');
        $permissionHelper = $this->container->get('permissions_helper');
        $request = $this->get('request');
        $menuRoot= $contentHelper->getMenuZero($login, $request);
        $menuId = $menuRoot->getId();
        $menus = $contentHelper->getMenus($menuId, $request);
        $menu_contents = $contentHelper->getMenuContent($menuId, $request);

        $menuTree = $this -> getMenuStructure($menuId, $login, $request);




    }


    /**
     * @Route("user/{login}/drive/menu/{element}/new/content",name="drive_item_new")
     * @Template()
     */
    public function NewDriveItemAction($login, $element)
    {
        $user = $this -> validateUser();


        // inicjalizacja flash baga
        $session = $this->container->get('session');

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {


                $request = $this->get('request');

                //helpery
                $contentHelper = $this->container->get('drive_content_helper');
                $permissionHelper = $this->container->get('permissions_helper');
                $moduleHelper = $this->container->get('module_helper');
                $moduleHelper->init('GitDrive');


                //pobieranie  i sprawdzanie tresci
                $pageContent = $this ->getMenuPageContent($user->getId(), $element, $request);

                $newContent = new Content();

                $form = $this->createForm(new DriveElementType(), $newContent);
                $form->handleRequest($request);

                if ($form->isValid()) {

                    $newContent ->setIdUser($user->getId());
                    $newContent->setLastModificationDate(new \DateTime('now'));
                    $newContent -> setIdMenu($pageContent);
                    $newContent->setCreateDate(new \DateTime('now'));
                    $contentHelper = $this->container->get('drive_content_helper');
                    $contentHelper -> insert($newContent);

                    $session->getFlashBag()->add('success', 'Dodano : '.$newContent->getTitle());


                    return $this->redirect($this->generateUrl('drive_show_menu', array(
                        'login'=>$login,
                        'element' => $element),true));
                    }




                return array(
                    'user' => $user,
                    'form' => $form->createView(),
                    'tytul' => "Nowy element",
                    'btnLabel' => 'dodaj',
                    'parentMenu' => $pageContent
                );



            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }
    }


    /**
     * @Route("user/{login}/drive/content/{element}/edit",name="drive_content_edit")
     * @Template("GitboxCoreBundle:Drive:NewDriveItem.html.twig")
     */
    public function EditDriveItemAction($login, $element)
    {

        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {


                $request = $this->get('request');

                //helpery
                $moduleHelper = $this->container->get('module_helper');
                $moduleHelper->init('GitDrive');


                //pobieranie i sprawdzanie danych
                $oldCon=$this ->getPageContent($user->getId(), $element, $request);
                $parentMenu= null;

                // inicjalizacja flash baga
                $session = $this->container->get('session');

                //formularz
                $form = $this->createForm(new DriveElementType(), $oldCon);
                $form->handleRequest($request);
                $pageContent = $this ->getPageContent($user->getId(), $element, $request);

                $pageContent->getIdMenu();

                if ($form->isValid()) {
                    $oldCon->setLastModificationDate(new \DateTime('now'));
                    $dcHelper = $this->container->get('drive_content_helper');
                    $dcHelper -> update($oldCon);

                    $session->getFlashBag()->add('success', 'Zmieniono element: ' . $oldCon->getTitle() );

                    return $this->redirect($this->generateUrl('drive_show_content', array(
                        'login'=>$login,
                        'element' => $element),true));
                }



                return array(
                    'user' => $user,
                    'form' => $form->createView(),
                    'tytul' => "Edytuj element",
                    'btnLabel' => 'edytuj',
                    'parentMenu' => $parentMenu

                );



            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }



    }




    /**
     * @Route("user/{login}/drive/content/{element}/remove",name="drive_content_remove")

     */
    public function RemoveDriveItemAction($login, $element)
    {
        $user = $this -> validateURL($login);
        $request = $this->get('request');
        $oldCon=$this ->getPageContent($user->getId(), $element, $request);

        $parent= $oldCon->getIdMenu()->getId();
        $form = $this->createForm(new DriveElementType(), $oldCon);
        $form->handleRequest($request);
        $pageContent = $this ->getPageContent($user->getId(), $element, $request);
        $this->userCheckContentX($pageContent);
        $this->removeContentx($oldCon,$request, $user);





            return $this->redirect($this->generateUrl('drive_show_menu', array(
                'login'=>$login,
                'element' => $parent),true));



    }


    /**
     * @Route("user/{login}/drive/content/{element}/upload",name="drive_upload")
     * @Template("GitboxCoreBundle:Drive:NewDriveItem.html.twig")
     */
    public function NewDriveAttachmentAction($login, $element)
    {
        $user = $this -> validateURL($login);
        $request = $this->get('request');


        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitDrive');
        $pageContent = $this ->getPageContent($user->getId(), $element, $request);
        $this->userCheckContentX($pageContent);

        $newAttachment = new Attachment();


        $form = $this->createForm(new DriveAttachmentType(), $newAttachment);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $newContent ->setIdUser($user->getId());
            $newContent->setLastModificationDate(new \DateTime('now'));
            $newContent -> setIdMenu($pageContent);
            $newContent->setCreateDate(new \DateTime('now'));
            $contentHelper = $this->container->get('drive_content_helper');
            $contentHelper -> insert($newContent);
            ;

            return $this->redirect($this->generateUrl('drive_show_menu', array(
                'login'=>$login,
                'element' => $element),true));
        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
            'tytul' => "Dodaj plik"

        );
    }

    /**
     * Usuwa content o podanym id
     *
     * @Route("/user/{login}/drive/content/{element}/att/{el2}/remove", name="att_remove_file")
     * @Method({"GET"})
     */
    public function removeAttAction($login, $element, $el2) {


        $user = $this -> validateUser();

        if(isset($user))
        {
            if($user-> getLogin() == $login)
            {


                $request = $this->get('request');

                //helpery
                $moduleHelper = $this->container->get('module_helper');
                $contentHelper = $this->container->get('drive_content_helper');
                $moduleHelper->init('GitDrive');

                //pobieranie i sprawdzenie elementow
                $oldCon=$this ->getPageContent($user->getId(), $element, $request);
                $parent= $oldCon->getIdMenu()->getId();

                $page_attachment = $contentHelper->getAttachmentById($oldCon,intval($el2));
                if($page_attachment != null){
                    $this -> removeAttx($user, $page_attachment);
                }



                return $this->redirect($this->generateUrl('drive_show_content', array(
                    'login'=>$login,
                    'element' => $element),true));







            }
            else
            {
                throw $this->createNotFoundException('Ten dysk nie należy do ciebie ! ');
            }
        }
        else
        {
            throw $this->createNotFoundException('Jesteś niezalogowany');
        }

    }
}
