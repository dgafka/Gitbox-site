<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EasyErrorHandlerControllerTest extends WebTestCase
{
    public function testAccountfailactivation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/accountFailActivation');
    }

    public function testAccountnotactive()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/accountNotActive');
    }

}
