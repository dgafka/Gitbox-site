<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RatingControllerTest extends WebTestCase
{
    public function testVote()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{login}/{module}/{id}/voteUp');
    }

}
