<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
<<<<<<< HEAD
     * @Route("/default/{name}")
=======

>>>>>>> 111d34895d3698cc5a95f117c385a59d3ebb3298
     * @Template()
     */
    public function indexAction($name)
    {
        $em = $this->getDoctrine();
	    /**
	     * @var $queryBuilder \Doctrine\ORM\QueryBuilder
	     */
	    $queryBuilder = $em->getManager()->createQueryBuilder();
		$name .= $name . ' dupa blada';
        return array('name' => $name);
    }

}
