<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Contact;
use App\Entity\Property;
use App\Form\ContactType;
use App\Entity\PropertyFilter;
use App\Form\PropertyFilterType;
use App\Repository\PropertyRepository;
use App\Notification\ContactNotification;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{
    /**
     *
     * @var PropertyRepository
     */
    private $repository;
    /**
     *
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
        //$this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PropertyFilter();
        $form = $this->createForm(PropertyFilterType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $properties
     * @return Response
     */
    public function show(Property $properties, string $slug, Request $request, ContactNotification $notification): Response
    {
        
        if ($properties->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $properties->getId(),
                'slug' => $properties->getSlug()
            ], 301);
        }

        //Creation du formulaire de contact
        $contact = new Contact();
        $contact->setProperty($properties);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('sucess', 'Votre email a bien été envoyer');
            return $this->redirectToRoute('property.show', [
                'id' => $properties->getId(),
                'slug' => $properties->getSlug()
            ]);
        }

        return $this->render('property/show.html.twig', [
            'properties' => $properties,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }
}
