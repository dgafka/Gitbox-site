<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{
    /**
     * @Route("/", name="home_url")
     * @Template()
     */
    public function indexAction()
    {
//	    /**
//	     * @var $queryBuilder QueryBuilder
//	     */
//	    $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
//	    $results = $queryBuilder
//			 ->select('m')
//		    ->from('\Gitbox\Bundle\CoreBundle\Entity\Menu', 'm')
//		    ->where('m.parent IS NULL')
//	       ->getQuery()
//	       ->execute();
//
////	    var_dump($results);
//	    $response = $this->forward('GitboxCoreBundle:Default:index', array(
//		    'name'  => "bla",
//	    ));
//	    var_dump($response->getContent());
//	    return array('response' => $response);

        return array();
    }

    /**
     * @Route("/search", name="search_url")
     * @Template()
     */
    public function searchAction()
    {
        return array();
    }

	/** Method generates urls for views.
    * @Template()
	 * @param $controller String
	 * @param $action String
	 * @return string|void
	 */
	public function generateUrlAction() {

		return array();
	}

}
