<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Manager\BadgeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardPropertyController extends AbstractController
{
    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     *
     * @var BadgeManager
     */
    private $badge_manager;

    public function __construct(EntityManagerInterface $em, BadgeManager $badge_manager)
    {
        $this->em = $em;
        $this->badge_manager = $badge_manager;
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
            $property_count = $this->em->getRepository(Property::class)->countForUser($this->getUser()->getId());
            $this->badge_manager->checkAndUnlock($this->getUser(), 'property', $property_count);
            $this->em->getConnection()->commit();

            $this->addFlash('success', 'Bien créé avec success');
            return $this->redirectToRoute('dashboard.property.index');
        }

        $badges = $this->badge_manager->getBadgeFor($this->getUser());

        return $this->render('dashboard_property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'badges' => $badges
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
