<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * Allow to set an array option 
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfigurations($label, $placeholder){
        $arrayOptions = [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];

        return $arrayOptions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfigurations("Titre de l'annonce", "Tapez un super titre"))
            ->add('slug', TextType::class, $this->getConfigurations("Slug de l'annonce", "Entrez le slug"))
            ->add('coverImage', UrlType::class, $this->getConfigurations("Url de l'image", "Ajoutez l'url de l'image principale"))
            ->add('introduction', TextType::class, $this->getConfigurations("Introduction", "Ecrivez une petite introduction"))
            ->add('content', TextareaType::class, $this->getConfigurations("Description", "Décrivez votre bien"))
            ->add('rooms', IntegerType::class, $this->getConfigurations("Nombre de chambres", "Renseignez le nombre de chambre"))
            ->add('price', MoneyType::class, $this->getConfigurations("Prix de l'annonce", "Entrez le prix"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
