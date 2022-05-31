<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, $this->getGonfiguration("Nom", "Tapez votre nom"))
            ->add('lastName', TextType::class, $this->getGonfiguration("Prénom", "Tapez votre prénom"))
            ->add('email', EmailType::class, $this->getGonfiguration("Email", "Entrez votre email"))
            ->add('pircture', UrlType::class, $this->getGonfiguration("Photo de profil", "URL de votre photo"))
            ->add('hash', PasswordType::class, $this->getGonfiguration("Mot de passe", "Entrez votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getGonfiguration("Confirmation de mot de passe", "Entrez votre conf mot de passe"))
            ->add('introduction', TextType::class, $this->getGonfiguration("Introduction", "Présentez vous en quelques mots"))
            ->add('description', TextareaType::class, $this->getGonfiguration("Description", "Parlez de vous en quelques mots !"));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
