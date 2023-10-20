<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ParcelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcelRepository::class)]
class Parcel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $estimatedCost = null;

    #[ORM\ManyToOne(inversedBy: 'parcels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sender $Sender = null;

    #[ORM\ManyToOne(inversedBy: 'parcels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipient $Recipient = null;

    #[ORM\ManyToOne(inversedBy: 'parcels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dimensions $dimensions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstimatedCost(): ?float
    {
        return $this->estimatedCost;
    }

    public function setEstimatedCost(float $estimatedCost): static
    {
        $this->estimatedCost = $estimatedCost;

        return $this;
    }

    public function getSender(): ?Sender
    {
        return $this->Sender;
    }

    public function setSender(?Sender $Sender): static
    {
        $this->Sender = $Sender;

        return $this;
    }

    public function getRecipient(): ?Recipient
    {
        return $this->Recipient;
    }

    public function setRecipient(?Recipient $Recipient): static
    {
        $this->Recipient = $Recipient;

        return $this;
    }

    public function getDimensions(): ?Dimensions
    {
        return $this->dimensions;
    }

    public function setDimensions(?Dimensions $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }
}
