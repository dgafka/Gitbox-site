<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{login}/blog');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{login}/blog/{id}');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{login}/blog/{id}/edit');
    }

}
