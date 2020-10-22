<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Entity\Property;
use App\Event\PropertyCreatedEvent;
use App\Form\PropertyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DashboardPropertyController extends AbstractController
{
    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/dashboard/property", name="dashboard.property.index")
     */
    public function index(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(Property::class);
        $propertiesByUser = $repository->findPropertyByUser($user->getId());

        $check_email_verify = $this->getDoctrine()->getRepository(User::class)->findOneById($user->getId());

        //Check if the user's email has been validated
        if ($check_email_verify->isVerified() == false) {
           $this->addFlash("email_no_verify", "Votre email n'a pas encore été vérifiée");
        }

        return $this->render('dashboard_property/index.html.twig', [
            'properties' => $propertiesByUser,
            'user' => $user
        ]);
    }

    /**
     * @Route("/dashboard/property/create", name="dashboard.property.new")
     */
    public function new(Request $request)
    {
        $property = new Property();
        $property->setUsers($this->getUser());
        $form = $this->createForm(PropertyType::class, $property); //Création du formulaire
        $form->handleRequest($request);

        //Add data in database
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($property);
            $this->em->getConnection()->beginTransaction();
            $this->em->flush();

            //Déblocage du badge
            $this->eventDispatcher->dispatch(new PropertyCreatedEvent($property), PropertyCreatedEvent::NAME);   
            
            $this->em->getConnection()->commit();

            $this->addFlash('success', 'Bien créé avec success');
            return $this->redirectToRoute('dashboard.property.index');
        }

        return $this->render('dashboard_property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/property/{id}", name="dashboard.property.edit", methods={"GET|POST"})
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property); //Création du formulaire
        $form->handleRequest($request);

        //Change the datas in the database
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
