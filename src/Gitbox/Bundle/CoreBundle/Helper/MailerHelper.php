<?php

namespace Gitbox\Bundle\CoreBundle\Helper;


class SwiftMailerHelper {

	private static $mailer;
	private static $message;

	public function __construct($mailer) {
		if(!isset(self::$mailer)) {
			self::$mailer = $mailer;
		}
	}

	/**
	 * Tworzy wiadomość
	 */
	public static function createMessage($title, $content, $email) {
		$message = \Swift_Message::newInstance()
			->setSubject($title)
			->setFrom('gitboxswiftmailer@gmail.com')
			->setTo($email)
			->setBody(
				$content, 'text/html', 'utf8'
			)
		;

		self::$message = $message;
	}

	/**
	 * Wysyła wcześniej utworzona wiadomość
	 */
	public function sendMessage() {
		self::$mailer->send(self::$message);
	}
} 