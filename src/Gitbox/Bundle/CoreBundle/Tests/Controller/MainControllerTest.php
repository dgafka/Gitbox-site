<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search');
    }

}
