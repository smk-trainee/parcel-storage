<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Address
{
    #[ORM\Column(type: 'string')]
    private ?string $country;
    #[ORM\Column(type: 'string')]
    private ?string $city;
    #[ORM\Column(type: 'string')]
    private ?string $street;
    #[ORM\Column(type: 'integer')]
    private ?int $house;
    #[ORM\Column(type: 'integer')]
    private ?int $apartment;

    public function __construct(?string $country, ?string $city, ?string $street, ?int $house, ?int $apartment)
    {
        $this->country = $country;
        $this->city = $city;
        $this->street = $street;
        $this->house = $house;
        $this->apartment = $apartment;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getHouse(): ?int
    {
        return $this->house;
    }

    public function getApartment(): ?int
    {
        return $this->apartment;
    }
}
