<?php

namespace App\Controller;

use App\Form\EditAccountType;
use App\Manager\BadgeManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccountController extends AbstractController
{
    /**
     *
     * @var BadgeManager
     */
    private $badge_manager;

    public function __construct(BadgeManager $badge_manager)
    {
        $this->badge_manager = $badge_manager;
    }

    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        $badges = $this->badge_manager->getBadgeFor($this->getUser());

        return $this->render('account/index.html.twig', [
            'badges' => $badges
        ]);
    }

    /**
     * @Route("/account/edit/profile", name="account_edit_profile")
     */
    public function editProfile(Request $request, TokenStorageInterface $tokenStorageInterface, SessionInterface $sessionInterface)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getLocale() !== $sessionInterface->get('_locale')) {
               $sessionInterface->set('_locale', $user->getLocale());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush(); 

            // Method 1 : Force logout user 
            // $tokenStorageInterface->setToken();
            // return $this->redirectToRoute('app_logout');

            // Method 2 : set locale if it has changed
            $this->addFlash('success', 'Profil mis à jour');

            return $this->redirectToRoute('account');
        }

        return $this->render('account/editaccount.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/edit/pass", name="account_edit_pass")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            //On vérifie si les deux mot de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($encoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('success', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('account');
            }
            else{
                $this->addFlash('error', 'Les deux mot de passe ne sont pas identiques');
            }
        }

        return $this->render('account/editpassword.html.twig');
    }
}
