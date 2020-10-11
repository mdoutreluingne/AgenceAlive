<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\ServerExtension;

class HomeControllerTest extends PantherTestCase
{

    public function testMyApp(): void
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $client->request('GET', '/');

        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('Agence alive');
    }
}
