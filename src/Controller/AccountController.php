<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Allow to show and administrate the form login
     * 
     * @Route("/login", name="account_login")
     * 
     * @param AuthenticationUtils $utils
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        // Get last error
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render(
            'account/login.html.twig',
            [
                'hasError' => $error != null,
                'lastUsername' => $username
            ]
        );
    }

    /**
     * Allow to logout
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout()
    {
        /* Nothing in this method, because it's Symfony which administrate the logout function */
    }

    /**
     * Allow to register an User
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        // Instanciation of a new user
        $user = new User();

        // Construction a form with the formBuilder
        $form = $this->createForm(RegistrationType::class, $user);

        //Handle http request in to form
        $form->handleRequest($request);

        // Test if form have been submitted and if it's valid
        if($form->isSubmitted() && $form->isValid()){
            // Hashing of user password
            $hash = $encoder->encodePassword($user, $user->getHash());

            // Save the new password which have been encrypted in to user information
            $user->setHash($hash);

            // We persit the new user entity
            $manager->persist($user);

            // We send the sql request in to database in order to save this new user
            $manager->flush();

            // Sending a flash success which show that the ad have been saving in to database
            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }
        // Render in to the view 
        return $this->render('account/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
