<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testAddNewUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink("S'inscrire")->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider')->form();
        $form['registration_form[username]'] = "Cartman";
        $form['registration_form[email]'] = "cartman.st@gmail.com";
        $form['registration_form[plainPassword]'] = "cartman";
        $form['registration_form[agreeTerms]'] = true;

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }
}
