<?php

namespace Gitbox\Bundle\CoreBundle\Helper;


class MailerHelper {

	/**
	 * @var \Swift_Mailer
	 */
	private static $mailer;
	private static $message;

	public function __construct($mailer) {
		if(!isset(self::$mailer)) {
			self::$mailer = $mailer;
		}
	}

	/** Tworzy nową wiadomość, gotową do wysłania
	 * @param $title Nazwa maila, przychodzącego do odbiorcy
	 * @param $content Treść maila przychodzączego do odbiorcy
	 * @param $email Email odbiorcy
	 * @return \Gitbox\Bundle\CoreBundle\Helper\MailerHelper
	 */
	public function createMessage($title, $content, $email) {
		$message = \Swift_Message::newInstance()
			->setSubject($title)
			->setFrom('gitboxswiftmailer@gmail.com')
			->setTo($email)
			->setBody(
				$content, 'text/html', 'utf8'
			)
		;

		self::$message = $message;

		return $this;
	}

	/**
	 * Wysyła wcześniej utworzona wiadomość
	 */
	public function sendMessage() {
		self::$mailer->send(self::$message);
	}
} 