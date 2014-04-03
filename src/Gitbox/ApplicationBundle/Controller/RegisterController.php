<?php

namespace Gitbox\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// Import new namespaces
use Gitbox\ApplicationBundle\Entity\UserAccount;
use Gitbox\ApplicationBundle\Form\EnquiryType;

class RegisterController extends Controller
{
    public function registerAction()
    {
        $enquiry = new UserAccount();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // Perform some action, such as sending an email

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('gitbox_application_register'));
            }
        }

        return $this->render('GitboxApplicationBundle:Register:register.html.twig', array(
            'form' => $form->createView()
        ));
    }
}