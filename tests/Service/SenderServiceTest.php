<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Sender;
use App\Repository\FullNameRepository;
use App\Repository\SenderRepository;
use App\Service\FullNameService;
use App\Service\SenderService;
use PHPUnit\Framework\TestCase;

class SenderServiceTest extends TestCase
{
    private SenderService $senderService;

    protected function setUp(): void
    {
        $this->senderService = new SenderService(
            $this->createMock(SenderRepository::class),
            $this->createMock(FullNameRepository::class),
            new FullNameService($this->createMock(FullNameRepository::class))
        );
    }

    /**
     * @throws \Exception
     */
    public function testSearchByPhone(): void
    {
        $this->expectException(\Exception::class);

        $data = '1234567980';

        $result = $this->senderService->searchByPhone($data);

        $this->assertIsArray($result);
    }

    public function testFindOrCreate(): void
    {
        $data = [
            'fullName' => [
                    'lastName' => 'test_lname',
                    'firstName' => 'test_fname',
                    'middleName' => 'test_mname',
            ],
            'phone' => '1234567890',
            'address' => 'test_address',
        ];

        $result = $this->senderService->findOrCreate($data);
        $this->assertInstanceOf(Sender::class, $result);
    }
}
