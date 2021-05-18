<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Fuel;
use App\Entity\Gearbox;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Titre',
            'required' => false
        ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix'
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Année du véhicule'
            ])
            ->add(
                'fuel',
                EntityType::class,
                [
                    'class' => Fuel::class,
                    'choice_label' => 'name',
                    'placeholder' => '--Choisir un carburant'
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'placeholder' => '--Choisir une catégorie'
                ]
            )
            ->add(
                'gearbox',
                EntityType::class,
                [
                    'class' => Gearbox::class,
                    'choice_label' => 'name',
                    'placeholder' => '--Choisir une boite de vitesse'
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Détails sur le véhicule'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
