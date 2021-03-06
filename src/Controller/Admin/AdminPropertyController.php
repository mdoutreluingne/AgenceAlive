<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Manager\BadgeManager;
use Doctrine\ORM\EntityManager;
use App\Event\PropertyCreatedEvent;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminPropertyController extends AbstractController
{
    /**
     *
     * @var PropertyRepository
     */
    private $repository;

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

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin_property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request)
    {
        $property = new Property();
        $property->setUsers($this->getUser());
        $form = $this->createForm(PropertyType::class, $property); //Création du formulaire
        $form->handleRequest($request);

        //Ajoute données dans la bdd
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->em->persist($property);
            $this->em->getConnection()->beginTransaction();
            $this->em->flush();

            //Déblocage du badge
            $this->eventDispatcher->dispatch(new PropertyCreatedEvent($property), PropertyCreatedEvent::NAME);
            
            $this->em->getConnection()->commit();

            $this->addFlash('success', 'Bien créé avec success');
            return $this->redirectToRoute('admin.property.index');
        }
        
        return $this->render('admin_property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods={"GET|POST"})
     */
    public function edit(Property $property, Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(PropertyType::class, $property); //Création du formulaire
        $form->handleRequest($request);

        //Change les données dans la bdd
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();

            $message = $translator->trans("Property modified successfully");
            $this->addFlash('success', $message);
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods={"DELETE"})
     */
    public function delete(Property $property, Request $request)
    {
        //Validation token Csrf
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token') )) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec success');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}
