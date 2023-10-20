<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Dimensions;
use App\Repository\DimensionsRepository;
use App\Service\DimensionsService;
use PHPUnit\Framework\TestCase;

class DimensionsServiceTest extends TestCase
{
    public function testFindOrCreate(): void
    {
        $params = [
            'weight' => 11.1,
            'length' => 12.2,
            'height' => 13.3,
            'width' => 14.4,
        ];

        $service = new DimensionsService($this->createMock(DimensionsRepository::class));

        $result = $service->findOrCreate($params);
        $this->assertInstanceOf(Dimensions::class, $result);
    }
}
