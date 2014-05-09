<?php
namespace Gitbox\Bundle\CoreBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DrivePermissionHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */

class DrivePermissionHelper   {

    /**
     * @var Session
     */
    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

public function checkUser() {
$username = $this->session->get('username');



if (!isset($username)) {
    return null;
}


return $username;
}
}