<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('OldPassword', PasswordType::class, $this->getGonfiguration("Ancien mot de passe", "Donnez votre mot de passe actuel.."))
            ->add('newPassword', PasswordType::class, $this->getGonfiguration("Nouveau mot de passe", "Nouveau mot de passe.."))
            ->add('confirmePassword', PasswordType::class, $this->getGonfiguration("Confirmation de votre nouveau mot de passe", "Confirmation du mot de passe"));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
