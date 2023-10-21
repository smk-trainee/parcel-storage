<?php

namespace App\Request;

use App\Dto\Sender;
use App\Dto\Recipient;
use App\Dto\Dimensions;
use Symfony\Component\Validator\Constraints as Assert;

class ParcelRequest extends BaseRequest
{
    #[Assert\NotNull]
    #[Assert\Valid]
    #[Assert\Type(Sender::class)]
    protected Sender $sender;

    #[Assert\NotNull]
    #[Assert\Valid]
    #[Assert\Type(Recipient::class)]
    protected Recipient $recipient;

    #[Assert\NotNull]
    #[Assert\Valid]
    #[Assert\Type(Dimensions::class)]
    protected Dimensions $dimensions;

    #[Assert\NotNull]
    #[Assert\Type("integer")]
    protected int $valuation;
}
