<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserAccountControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Register');
    }

    public function testRegistersubmit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/RegisterSubmit');
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Login');
    }

    public function testLoginsubmit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/LoginSubmit');
    }

}
