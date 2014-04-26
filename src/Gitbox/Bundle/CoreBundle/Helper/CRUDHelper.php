<?php

namespace Gitbox\Bundle\CoreBundle\Helper;


/**
 * Interfejs do implementacji Helpera wykorzystującego 4 podstawowe operacje bazodanowe (CRUD)
 *
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
interface CRUDHelper {

	public function find($object);

	public function remove($object);

	public function update($object);

	public function insert($object);

} 