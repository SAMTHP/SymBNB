<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'fistName', 
                TextType::class,
                $this->getConfigurations("Prénom","Entrez votre prénom")
            )
            ->add(
                'lastName', 
                TextType::class,
                $this->getConfigurations("Nom","Entrez votre nom")
            )
            ->add(
                'email', 
                EmailType::class,
                $this->getConfigurations("Email","Entrez votre email")
            )
            ->add(
                'picture', 
                UrlType::class,
                $this->getConfigurations("Photo de profil","Ajoutez votre avatar",['required' => true])
            )
            ->add(
                'hash', 
                PasswordType::class,
                $this->getConfigurations("Mot de passe","Entrez votre mot de passe")
            )
            ->add(
                'introduction', 
                TextType::class,
                $this->getConfigurations("Introduction","Présentez vous en quelques mots")
            )
            ->add(
                'description', 
                TextareaType::class,
                $this->getConfigurations("Description détaillée","Décrivez-vous en détail")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
