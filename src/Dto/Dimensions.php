<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
final class Dimensions
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $weight,

        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $length,

        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $height,

        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public readonly int $width,

    ) {
    }
}
