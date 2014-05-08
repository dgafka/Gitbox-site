<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testUsermanagment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/userManagment');
    }

    public function testIpmanagment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/ipManagment');
    }

    public function testMainsitemanagment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/mainSiteManagment');
    }

    public function testAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin');
    }

}
