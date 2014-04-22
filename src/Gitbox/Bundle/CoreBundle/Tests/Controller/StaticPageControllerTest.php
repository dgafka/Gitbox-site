<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StaticPageControllerTest extends WebTestCase
{
    public function testFeatures()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/features');
    }

    public function testHelp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/help');
    }

    public function testGitblog()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gitblog');
    }

    public function testGitdrive()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gitdrive');
    }

    public function testGittube()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gittube');
    }

}
