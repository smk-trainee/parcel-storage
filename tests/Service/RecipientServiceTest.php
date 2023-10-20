<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Recipient;
use App\Repository\FullNameRepository;
use App\Repository\RecipientRepository;
use App\Service\FullNameService;
use App\Service\RecipientService;
use PHPUnit\Framework\TestCase;

class RecipientServiceTest extends TestCase
{
    private RecipientService $recipientService;

    protected function setUp(): void
    {
        $this->recipientService = new RecipientService(
            $this->createMock(FullNameRepository::class),
            $this->createMock(FullNameService::class),
            $this->createMock(RecipientRepository::class),
        );
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

        $result = $this->recipientService->findOrCreate($data);

        $this->assertInstanceOf(Recipient::class, $result);
    }

    public function testsSearchByFullName(): void
    {
        $this->expectException(\Exception::class);

        $data = [
            'lastName' => 'testLastName',
            'firstName' => 'testFirstName',
            'middleName' => 'testMiddleName',
        ];

        $result = $this->recipientService->searchByFullName($data);
        $this->assertIsArray($result);
    }
}
