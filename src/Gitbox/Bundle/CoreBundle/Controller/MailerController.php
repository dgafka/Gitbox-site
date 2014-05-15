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
			    "Aktywacja konta dla Gitbox",
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
    public function recoveryPasswordAction($user, $password)
    {
	    /**
	     * @var $mailerHelper \Gitbox\Bundle\CoreBundle\Helper\MailerHelper
	     */
	    $mailerHelper = $this->get('mailer_helper')
		    ->createMessage(
			    "Odzyskiwanie hasła dla Gitbox",
			    $this->renderView(
				    'GitboxCoreBundle:Mailer:recoveryPasswordMail.html.twig',
				    array(
					    'password' => $password,
				    )
			    ),
			    $user->getEmail()
		    );

	    $mailerHelper->sendMessage();

        $session = $this->container->get('session');
        $session->getFlashBag()->add('success', 'Email z nowym hasłem został wysłany.');

	    return array();
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
            $session = $this->container->get('session');
            $session->getFlashBag()->add('warning', 'Podany token nie istnieje. Czy na pewno nie aktywowałeś konta już wcześniej?');

            return $this->redirect($this->generateUrl('home_url'));
	    }
		if($userAccount->getStatus() != 'D') {
            $session = $this->container->get('session');
            $session->getFlashBag()->add('warning', 'Konto było już wcześniej aktywowane!');

            return $this->redirect($this->generateUrl('home_url'));
		}

	    $userAccount->setStatus('A');
	    $userDescription = $userAccount->getIdDescription();
	    $userDescription->setToken(null);

	    $helper->update($userAccount);

	    return array('login' => $userAccount->getLogin());
    }

	/** Wysyła maila do administratora z informacją o pomocy
	 * @param $email
	 * @param $content
	 */
	public function helpMailAction($email, $content) {
		/**
		 * @var $mailerHelper \Gitbox\Bundle\CoreBundle\Helper\MailerHelper
		 */
		$mailerHelper = $this->get('mailer_helper')
			->createMessage(
				"Pomoc - Gitbox",
				$this->renderView(
					'GitboxCoreBundle:Mailer:helpMail.html.twig',
					array(
						'email'   => $email,
						'content' => $content
					)
				),
				'gitboxswiftmailer@gmail.com'
			);

		$mailerHelper->sendMessage();

		$session = $this->container->get('session');
		$session->getFlashBag()->add('success', 'Email został wysyłany. Odpowiemy w przeciągu 24 godzin.');

		return $this->redirect($this->generateUrl('home_url'));
	}
}
