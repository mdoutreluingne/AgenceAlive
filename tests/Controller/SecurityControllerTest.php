<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'S\'identifier');
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'unknown',
            'password' => 'fakepassword'
        ]);
        //Envoie les données au formulaire
        $client->submit($form);
        //Redige après la soumission du formulaire
        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'Cartman',
            'password' => 'cartman'
        ]);
        //Envoie les données au formulaire
        $client->submit($form);
        //Redige après la soumission du formulaire
        $crawler = $client->followRedirect();
        
        $this->assertSelectorTextContains('h3', 'Gérer vos biens');
        $this->assertResponseStatusCodeSame(200);
    }
}
