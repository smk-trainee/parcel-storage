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

    #[ORM\Embedded(class: Sender::class)]
    private ?Sender $sender;

    #[ORM\Embedded(class: Recipient::class)]
    private ?Recipient $recipient;
    #[ORM\Embedded(class: Dimensions::class)]
    private ?Dimensions $dimensions;

    #[ORM\Column(type: 'integer')]
    private ?int $valuation;

    public function __construct(?Sender $sender, ?Recipient $recipient, ?Dimensions $dimensions, ?int $valuation)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->dimensions = $dimensions;
        $this->valuation = $valuation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Sender
    {
        return $this->sender;
    }

    public function getRecipient(): ?Recipient
    {
        return $this->recipient;
    }

    public function getDimensions(): ?Dimensions
    {
        return $this->dimensions;
    }

    public function getValuation(): ?int
    {
        return $this->valuation;
    }
}
