<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FullName;
use App\Repository\FullNameRepository;

class FullNameService
{
    public function __construct(private readonly FullNameRepository $fullNameRepo)
    {
    }

    public function addNewFullName(array $fullNameData): FullName
    {
        $fullName = new FullName();
        $fullName
            ->setFirstName($fullNameData['firstName'])
            ->setLastName($fullNameData['lastName'])
            ->setMiddleName($fullNameData['middleName']);
        $this->fullNameRepo->save($fullName, true);

        return $fullName;
    }
}
