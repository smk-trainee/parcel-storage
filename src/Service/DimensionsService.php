<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Dimensions;
use App\Repository\DimensionsRepository;

class DimensionsService
{
    public function __construct(private readonly DimensionsRepository $dimensionsRepo)
    {
    }

    public function findOrCreate(array $params): Dimensions
    {
        $dimensions = $this->dimensionsRepo->findOneBy([
            'weight' => $params['weight'],
            'length' => $params['length'],
            'height' => $params['height'],
            'width' => $params['width'],
        ]);

        if (!$dimensions instanceof Dimensions) {
            $dimensions = new Dimensions();
            $dimensions
                ->setWeight($params['weight'])
                ->setLength($params['length'])
                ->setHeight($params['height'])
                ->setWidth($params['width'])
            ;

            $this->dimensionsRepo->save($dimensions, true);
        }

        return $dimensions;
    }
}
