<?php

namespace App\Controller\Dashboard;

use App\Entity\Property;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavorisPropertyController extends AbstractController
{
    /**
     * @Route("/favoris/property", name="favoris_property")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Property::class);
        $favorisPropertiesByUser = $repository->findFavorisPropertyByUser($this->getUser());

        return $this->render('dashboard_property/favoris_index.html.twig', [
            'favorisProperties' => $favorisPropertiesByUser,
        ]);
    }
}
