<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserSidebarControllerTest extends WebTestCase
{
    public function testRender()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/render');
    }

}
