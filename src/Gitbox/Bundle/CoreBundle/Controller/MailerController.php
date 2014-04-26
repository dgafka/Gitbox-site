<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class MailerController extends Controller
{
    /** Metoda wysyła maila aktywującego dla zarejestrowanego użytkownika
     * @param $user \Gitbox\Bundle\CoreBundle\Entity\UserAccount
     * @Template()
     * @return array
     */
    public function accountActivationAction($user)
    {
	    /**
	     * @var $mailerHelper \Gitbox\Bundle\CoreBundle\Helper\MailerHelper
	     */
	    $mailerHelper = $this->get('mailer_helper')
		    ->createMessage(
			    "test",
			    $this->renderView(
		            'GitboxCoreBundle:Mailer:accountActivationMail.html.twig',
		            array(
			            'login' => $user->getLogin(),
		                'token' => $user->getIdDescription()->getToken(),
		            )
	            ),
			    $user->getEmail()
		    );

	    $mailerHelper->sendMessage();

	    return array("login" => $user->getLogin());
    }

    /** Odzyskuje hasło użytkownika, poprzez wysłanie maila
     * @Template()
     */
    public function recoveryPasswordAction()
    {
	    array('tmp' => 'tmp');
    }

    /** Aktywuje konto poprzez token, który wcześniej był wysłany na maila użytkownika
     * @Route("/accountActivationURL/{token}", name="account_activation_URL")
     * @Template()
     */
    public function accountActivationURLAction($token)
    {
	    /**
	     * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
	     */
	    $helper  = $this->container->get('user_helper');
	    $userAccount = $helper->findByToken($token);

	    if(!($userAccount instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount)) {
		    throw new NotFoundHttpException($this->renderView('GitboxCoreBundle:Error:notFound.html.twig'));
	    }
		if($userAccount->getStatus() != 'D') {
			throw new AuthenticationException($this->renderView('GitboxCoreBundle:Error:accountFailActivation.html.twig'));
		}

	    $userAccount->setStatus('A');
	    $userDescription = $userAccount->getIdDescription();
	    $userDescription->setToken(null);

	    $helper->update($userAccount);
		$helper->update($userDescription);

	    return array('login' => $userAccount->getLogin());
    }

}
