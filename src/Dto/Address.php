<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class Address
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type("string")]
        public readonly string $country,

        #[Assert\NotBlank]
        #[Assert\Type("string")]
        public readonly string $city,

        #[Assert\NotBlank]
        #[Assert\Type("string")]
        public readonly string $street,

        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $house,

        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $apartment,

    ) {
    }
}
