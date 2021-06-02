<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Brand;
use App\Entity\Fuel;
use App\Entity\Gearbox;
use App\Entity\Category;
use App\Entity\Locality;
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
                    'label' => 'Carburant',
                    'choice_label' => 'name',
                    'placeholder' => '--Choisir un carburant'
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'label' => 'Catégorie',
                    'choice_label' => 'name',
                    'placeholder' => '--Choisir une catégorie'
                ]
            )
            ->add(
                'gearbox',
                EntityType::class,
                [
                    'class' => Gearbox::class,
                    'label' => 'Transmission',
                    'choice_label' => 'name',
                    'placeholder' => '--Choisir une boite de vitesse'
                ]
            )
            ->add(
                'brand',
                EntityType::class,
                [
                    'class' => Brand::class,
                    'label' => 'Marque',
                    'choice_label' => 'title',
                    'placeholder' => '--Marque de votre véhicule'
                ]
            )

            ->add(
                'locality',
                EntityType::class,
                [
                    'class' => Locality::class,
                    'label' => 'lieu',
                    'choice_label' => 'name',
                    'placeholder' => '--lieu de votre véhicule'
                ]
            )

            ->add('images', FileType::class, [
                'label' => 'Ajoutez les photos de votre véhicule',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])

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
