<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ParcelDto
{
    public function __construct(

        #[Assert\Valid]
        public readonly Sender $sender,

        #[Assert\Valid]
        public readonly Recipient $recipient,

        #[Assert\Valid]
        public readonly Dimensions $dimensions,

        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $valuation
    ) {
    }
}
