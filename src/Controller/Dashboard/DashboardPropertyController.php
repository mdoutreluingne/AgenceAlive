<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardPropertyController extends AbstractController
{
    /**
     * @Route("/dashboard/property", name="dashboard.property")
     */
    public function index()
    {
        return $this->render('dashboard_property/index.html.twig', [
            'controller_name' => 'DashboardPropertyController',
        ]);
    }
}
