<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Form\Type\TubePostType;

class TubeController extends Controller
{
    /**
     * @Route("/user/{login}/tube", name="tube_index")
     * @Template()
     */
    public function indexAction($login)
    {

	    $helper = $this->container->get('user_helper');

        $contentHelper = $this->container->get('tube_content_helper');

        $user = $helper->findByLogin($login);

	    $posts = $contentHelper->getContents($login);

        return array('user' => $user, 'posts' => $posts);
    }

    /**
     * @Route("user/{login}/tube/{id}", name="content_show")
     * @Template()
     */
    public function showAction($login, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxCoreBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }


        return array(
            'entity'      => $entity,

        );
    }

}
