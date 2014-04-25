<?php
namespace Gitbox\Bundle\CoreBundle\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/** Klasa abstrakcyjna po której powinny dziedzić helpery do wyciągania danych z bazy.
 * Class Helper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
abstract class Helper {

	/**
	 * @var ObjectManager
	 */
	protected static $em;

	/** Pobranie entity managera z containera symfony
	 * @param $entityManager
	 */
	public function __construct($entityManager) {
		self::$em = $entityManager;
	}

	public function instance() {
		if(!isset(self::$em)) {
			self::$em = $this->getDoctrine()->getManager();
		}

		return self::$em;
	}

	public abstract function find($object);

	public abstract function remove($object);

	public abstract function update($object);

	public abstract function insert($object);

} 