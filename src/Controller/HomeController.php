<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PropertyRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(PropertyRepository $repository, LoggerInterface $logger): Response
    {
        $this->logger->info("Notre premier log");
        $properties = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'properties' => $properties
        ]);
    }
}
