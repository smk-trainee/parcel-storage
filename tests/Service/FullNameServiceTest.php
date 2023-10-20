<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\FullName;
use App\Repository\FullNameRepository;
use App\Service\FullNameService;
use PHPUnit\Framework\TestCase;

class FullNameServiceTest extends TestCase
{
    public function testFullNameService(): void
    {
        $data = [
            'firstName' => 'test_fname',
            'lastName' => 'test_lname',
            'middleName' => 'test_mname',
        ];

        $service = new FullNameService($this->createMock(FullNameRepository::class));
        $result = $service->addNewFullName($data);

        $this->assertInstanceOf(FullName::class, $result);
    }
}
