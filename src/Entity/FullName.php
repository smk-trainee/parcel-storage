<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class FullName
{
    #[ORM\Column(type: 'string')]
    private ?string $firstName;
    #[ORM\Column(type: 'string')]
    private ?string $lastName;
    #[ORM\Column(type: 'string')]
    private ?string $middleName;

    public function __construct(?string $firstName, ?string $lastName, ?string $middleName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getFullName(): ?string
    {
        return $this->lastName . ' ' . $this->middleName . ' ' . $this->middleName;
    }
}
