<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\Bundle\CoreBundle\Form\Type\DriveElementType;

class DriveController extends Controller
{

    /**
     * @Route("/user/{login}/drive")
     * @Template()
     */
    public function DriveAction($login)
    {   
    
    $form = $this->createForm(new DriveElementType());
     return array(
        'form' => $form->createView()
    );
    }

    /**
     * @Route("/user/{login}/drive/new")
     * @Template()
     */
    public function NewDriveItemAction($login)
    {	
	
	$form = $this->createForm(new DriveElementType());
	 return array(
		'form' => $form->createView()
	);
    }

}
