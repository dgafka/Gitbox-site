<?php

namespace Gitbox\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailerControllerTest extends WebTestCase
{
    public function testAccountactivation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/accountActivation');
    }

    public function testRecoverypassword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/recoveryPassword');
    }

    public function testAccountactivationurl()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/accountActivationURL');
    }

}
