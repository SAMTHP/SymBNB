<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'fistName',
                TextType::class,
                [
                    'label' => 'Prénom'
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Nom'
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email'
                ]
            )
            ->add(
                'picture',
                UrlType::class,
                [
                    'label' => 'URL de l\'avatar'
                ]
            )
            ->add(
                'introduction',
                TextType::class,
                [
                    'label' => 'Introduction'
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description'
                ]
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
