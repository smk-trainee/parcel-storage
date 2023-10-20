<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SenderRepository::class)]
class Sender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'Sender', targetEntity: Parcel::class)]
    private Collection $parcels;

    #[ORM\ManyToOne(inversedBy: 'senders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FullName $fullName = null;

    public function __construct()
    {
        $this->parcels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Parcel>
     */
    public function getParcels(): Collection
    {
        return $this->parcels;
    }

    public function addParcel(Parcel $parcel): static
    {
        if (!$this->parcels->contains($parcel)) {
            $this->parcels->add($parcel);
            $parcel->setSender($this);
        }

        return $this;
    }

    public function removeParcel(Parcel $parcel): static
    {
        if ($this->parcels->removeElement($parcel)) {
            // set the owning side to null (unless already changed)
            if ($parcel->getSender() === $this) {
                $parcel->setSender(null);
            }
        }

        return $this;
    }

    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    public function setFullName(?FullName $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }
}
