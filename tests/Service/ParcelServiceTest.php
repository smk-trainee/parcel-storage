<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\ParcelRepository;
use App\Service\DimensionsService;
use App\Service\ParcelService;
use App\Service\RecipientService;
use App\Service\SenderService;
use PHPUnit\Framework\TestCase;

class ParcelServiceTest extends TestCase
{

    private ParcelService $parcelService;
    protected function setUp(): void
    {
        $this->parcelService = new ParcelService(
            $this->createMock(ParcelRepository::class),
            $this->createMock(SenderService::class),
            $this->createMock(RecipientService::class),
            $this->createMock(DimensionsService::class),
        );
    }

    public function testDelete(): void
    {
        $this->expectException(\Exception::class);

        $id = 123;

        $result = $this->parcelService->delete($id);
        $this->assertEquals('ok', $result);
    }

    public function testAddNewParcel(): void
    {
        $this->expectException(\Exception::class);

        $data = '{
    "id": "string",
    "sender": {
      "fullName": {
        "firstName": "string",
        "lastName": "string",
        "middleName": "string"
      },
      "address": "string",
      "phone": "string"
    },
    "receiver": {
      "fullName": {
        "firstName": "string",
        "lastName": "string",
        "middleName": "string"
      },
      "address": "string",
      "phone": "string"
    },
    "dimensions": {
    {
      "weight": 0,
      "length": 0,
      "height": 0,
      "width": 0
    }
    },
    "estimatedCost": 0
  }';


        $result = $this->parcelService->addNewParcel($data);
        $this->assertEquals('ok', $result);
    }
}
