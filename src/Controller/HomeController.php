<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(PropertyRepository $repository): Response
    {
        $properties = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     * @return Response
     */
    public function changeLocale($locale, Request $request): Response
    {
        //On stocke la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);

        //On revien sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
