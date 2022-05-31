<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{


    private $OldPassword;

    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au min 8 cara !")
     */

    private $newPassword;

    /**
     * 
     * @Assert\EqualTo(propertyPath="newPassword", message="Vous n'avez pas correctement confirmé votre nouveau mot de passe")
     */

    private $confirmePassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldPassword(): ?string
    {
        return $this->OldPassword;
    }

    public function setOldPassword(string $OldPassword): self
    {
        $this->OldPassword = $OldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmePassword(): ?string
    {
        return $this->confirmePassword;
    }

    public function setConfirmePassword(string $confirmePassword): self
    {
        $this->confirmePassword = $confirmePassword;

        return $this;
    }
}
