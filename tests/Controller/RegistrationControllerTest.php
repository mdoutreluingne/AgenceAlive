<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testAddNewUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        //Clique sur le lien S'inscrire de la homepage
        $link = $crawler->selectLink("S'inscrire")->link();
        $crawler = $client->click($link);

        //Remplie les champs du formulaire
        $form = $crawler->selectButton('Valider')->form();
        $form['registration_form[username]'] = "Cartman";
        $form['registration_form[email]'] = "cartman.st@gmail.com";
        $form['registration_form[plainPassword]'] = "cartman";
        $form['registration_form[agreeTerms]'] = true;

        //Envoie les données au formulaire
        $client->submit($form);
        //Redige après la soumission du formulaire
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }
}
