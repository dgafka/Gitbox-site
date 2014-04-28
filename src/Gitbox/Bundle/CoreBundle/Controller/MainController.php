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
