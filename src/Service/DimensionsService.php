<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Dimensions;
use App\Interfaces\FindOrCreateInterface;
use App\Repository\DimensionsRepository;

class DimensionsService implements FindOrCreateInterface
{
    public function __construct(private readonly DimensionsRepository $dimensionsRepo)
    {
    }

    public function findOrCreate(array $data): Dimensions
    {
        $dimensions = $this->dimensionsRepo->findOneBy([
            'weight' => $data['weight'],
            'length' => $data['length'],
            'height' => $data['height'],
            'width' => $data['width'],
        ]);

        if (!$dimensions instanceof Dimensions) {
            $dimensions = new Dimensions();
            $dimensions
                ->setWeight($data['weight'])
                ->setLength($data['length'])
                ->setHeight($data['height'])
                ->setWidth($data['width'])
            ;

            $this->dimensionsRepo->save($dimensions, true);
        }

        return $dimensions;
    }
}
