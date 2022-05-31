<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getGonfiguration("Titre", "Tapez un titre pour votre annonce")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getGonfiguration("Adresse web", "Tapez votre adress web (automatique)", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getGonfiguration("URL de l'image principale", "Donnez l'adresse d'une image qui donne vraiment envie")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getGonfiguration("Introduction", "Donnez une description pour votre annonce")
            )
            ->add(
                'contents',
                TextareaType::class,
                $this->getGonfiguration("Annonce détaillée", "Donnez plus de description")
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getGonfiguration("Nombre de chambres", "Le nombre de chambre disponibles")
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getGonfiguration("Prix par nuit", "Le prix que vous voulez")
            )

            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
