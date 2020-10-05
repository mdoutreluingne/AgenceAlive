<?php

namespace App\Controller\Dashboard;

use App\Entity\Property;
use App\Form\PropertyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardPropertyController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/dashboard/property", name="dashboard.property.index")
     */
    public function index(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(Property::class);
        $propertiesByUser = $repository->findPropertyByUser($user->getId());

        //dd($propertiesByUser);
        return $this->render('dashboard_property/index.html.twig', [
            'properties' => $propertiesByUser,
        ]);
    }

    /**
     * @Route("/dashboard/property/create", name="dashboard.property.new")
     */
    public function new(Request $request, UserInterface $user)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property); //Création du formulaire
        $form->handleRequest($request);

        //Ajoute données dans la bdd
        if ($form->isSubmitted() && $form->isValid()) {
            $property->setUsers($user);
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec success');
            return $this->redirectToRoute('dashboard.property.index');
        }

        return $this->render('dashboard_property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/property/{id}", name="dashboard.property.edit", methods={"GET|POST"})
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property); //Création du formulaire
        $form->handleRequest($request);

        //Change les données dans la bdd
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec success');
            return $this->redirectToRoute('dashboard.property.index');
        }

        return $this->render('dashboard_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/property/{id}", name="dashboard.property.delete", methods={"DELETE"})
     */
    public function delete(Property $property, Request $request)
    {
        //Validation token Csrf
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec success');
        }
        return $this->redirectToRoute('dashboard.property.index');
    }
}
