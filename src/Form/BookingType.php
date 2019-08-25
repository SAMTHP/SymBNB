<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                TextType::class,
                $this->getConfigurations("Date d'arrivée","La date à laquelle vous comptez arriver")
            )
            ->add(
                'endDate',
                TextType::class,
                $this->getConfigurations("Date de départ","La date à laquelle vous quittez les lieux")
            )
            ->add(
                'comment',
                TextareaType::class,
                $this->getConfigurations(false,"Si vous avez un commentaire, n'hésitez pas à nous en faire part !",['required' => false])
            )
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
