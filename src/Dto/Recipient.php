<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class Recipient
{
    public function __construct(
        #[Assert\Valid]
        public readonly FullName $fullName,

        #[Assert\NotBlank]
        #[Assert\Type("string")]
        public readonly string $phone,

        #[Assert\Valid]
        public readonly Address $address,
    ) {
    }
}
