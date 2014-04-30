<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

    public function testModules()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/modules/{user}');
    }

    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{user}/about');
    }

}
