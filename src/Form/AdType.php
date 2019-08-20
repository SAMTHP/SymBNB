<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ApplicationType;
use App\Form\ImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfigurations("Titre de l'annonce", "Tapez un super titre")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfigurations("Slug de l'annonce", "Entrez le slug",[
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfigurations("Url de l'image", "Ajoutez l'url de l'image principale")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfigurations("Introduction", "Ecrivez une petite introduction")
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfigurations("Description", "Décrivez votre bien")
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfigurations("Nombre de chambres", "Renseignez le nombre de chambre")
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfigurations("Prix de l'annonce", "Entrez le prix")
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
