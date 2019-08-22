<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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

    /**
     * Allow to edit an user
     *
     * @Route("/account/profile", name="account_profile")
     * 
     * @return Response
     */
    public function profile(ObjectManager $manager, Request $request)
    {   
        // We get the current user
        $user = $this->getUser();

        // Construction a form with the formBuilder
        $form = $this->createForm(AccountType::class, $user);

        //Handle http request in to form
        $form->handleRequest($request);

        // Test if form have been submitted and if it's valid
        if($form->isSubmitted() && $form->isValid()){
            // We persit the edition of user entity
            $manager->persist($user);

            // We send the sql request in to database in order to save the edition of current user
            $manager->flush();

            // Sending a flash success which show that the ad have been saving in to database
            $this->addFlash(
                'success',
                "Votre compte a été modifié avec succès !"
            );

            //return $this->redirectToRoute('ads_index');
        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Allow to update the password
     *
     * @Route("/account/password-update", name="account_password")
     * 
     * @return Response
     */
    public function updatePassword(ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder){

        $user = $this->getUser();

        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // We verify if old password equal to user password
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
            }else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user,$newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                // Sending a flash success which show that the ad have been saving in to database
                $this->addFlash(
                    'success',
                    "Votre mot de passe a été modifié avec succès !"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Allow to show accoun of an user which is connected
     *
     * @Route("/account", name="account_index")
     * 
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }
}
