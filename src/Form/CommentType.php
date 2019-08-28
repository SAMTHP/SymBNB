<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'rating',
                IntegerType::class,
                $this->getConfigurations("Note sur 5","Entrez une note comprise entre 0 et 5",[
                    'attr' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 1
                    ]
                ])
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfigurations("Commentaire","Dites nous vos impréssions...")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
