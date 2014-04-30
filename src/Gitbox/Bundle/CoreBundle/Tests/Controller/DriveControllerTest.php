<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DriveControllerTest extends WebTestCase
{
    public function testNewdriveitem()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{login}/drive/new');
    }

}
