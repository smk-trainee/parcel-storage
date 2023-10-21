<?php

declare(strict_types=1);

namespace App\Model;

use App\Dto\Dimensions;
use App\Dto\Recipient;
use App\Dto\Sender;

class ParcelListItem
{
    private int $id;
    private Sender $sender;
    private Recipient $recipient;
    private Dimensions $dimensions;
    private int $valuation;

    public function __construct(int $id, Sender $sender, Recipient $recipient, Dimensions $dimensions, int $valuation)
    {
        $this->id = $id;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->dimensions = $dimensions;
        $this->valuation = $valuation;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    public function getDimensions(): Dimensions
    {
        return $this->dimensions;
    }

    public function getValuation(): int
    {
        return $this->valuation;
    }
}
