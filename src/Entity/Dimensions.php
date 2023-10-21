<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Dimensions
{
    #[ORM\Column(type: 'integer')]
    private ?int $weight;
    #[ORM\Column(type: 'integer')]
    private ?int $length;
    #[ORM\Column(type: 'integer')]
    private ?int $height;
    #[ORM\Column(type: 'integer')]
    private ?int $width;

    public function __construct(?int $weight, ?int $length, ?int $height, ?int $width)
    {
        $this->weight = $weight;
        $this->length = $length;
        $this->height = $height;
        $this->width = $width;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }
}
