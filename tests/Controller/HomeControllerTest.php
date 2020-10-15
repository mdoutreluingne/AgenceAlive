<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        //$this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseStatusCodeSame(200);

        //echo $client->getResponse()->getContent();
    }

    public function testTitleHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', "Agence alive");
    }

    public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/dashboard/property');

        $this->assertResponseRedirects('/login');

    }
}
