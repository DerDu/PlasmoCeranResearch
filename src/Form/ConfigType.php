<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Config;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article', EntityType::class, [
                'label' => 'Produkt',
                'class' => Article::class,
                'choice_label' => 'name'
            ])
            ->add('voltageStart')
            ->add('voltageLimit')
            ->add('voltageHysteresis')
            ->add('voltageStep')

            ->add('currentLimit')

            ->add('intensityLimit')
            ->add('intensityThreshold')
            ->add('intensityHysteresis')

            ->add('temperatureMaximum', NumberType::class)
            ->add('overlayText')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class
        ]);
    }
}
