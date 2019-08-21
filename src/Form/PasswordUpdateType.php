<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'oldPassword',
                PasswordType::class,
                $this->getConfigurations('Ancien mot de passe',"Entrez l'ancien mot de passe...")
            )
            ->add(
                'newPassword',
                PasswordType::class,
                $this->getConfigurations('Nouveau mot de passe',"Entrez un nouveau mot de passe...")
            )
            ->add(
                'confirmPassword',
                PasswordType::class,
                $this->getConfigurations('Confirmation du nouveau mot de passe',"Confirmez le nouveau mot de passe...")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
