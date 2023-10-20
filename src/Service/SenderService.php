<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FullName;
use App\Entity\Parcel;
use App\Entity\Sender;
use App\Repository\FullNameRepository;
use App\Repository\SenderRepository;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class SenderService
{
    public function __construct(
        private readonly SenderRepository $senderRepo,
        private readonly FullNameRepository $fullNameRepo,
        private readonly FullNameService $fullNameService
    ) {
    }

    public function findOrCreate(array $data): Sender
    {
        $fullNameData = $data['fullName'];

        try {
            $sender = $this->fullNameRepo->findOneBy([
                'firstName' => $fullNameData['firstName'],
                'lastName' => $fullNameData['lastName'],
                'middleName' => $fullNameData['middleName'],
            ]);
            if ($sender instanceof FullName) {
                return $sender->getSenders()->last();
            }

            return $this->createNewSender($fullNameData, $data['phone'], $data['address']);
        } catch (\Exception) {
            $sender = $this->createNewSender($fullNameData, $data['phone'], $data['address']);
        }

        return $sender;
    }

    /**
     * @throws \Exception
     */
    public function searchByPhone(string $searchValue): array
    {
        $sender = $this->senderRepo->findOneBy(['phone' => $searchValue]);
        if (!$sender instanceof Sender) {
            throw new \Exception('not found');
        }

        return $this->formatSearchData($sender);
    }

    private function formatSearchData(Sender $sender): array
    {
        $parcels = $sender->getParcels();
        if (0 === $parcels->count()) {
            throw new InvalidTypeException('no parcels');
        }
        $result = [];

        /** @var Parcel $parcel */
        foreach ($parcels as $parcel) {
            $result[] = [
                'id' => $sender->getId(),
                'sender' => [
                    'fullname' => [
                        $sender->getFullName()->getFullNameArray(),
                    ],
                    'phone' => $sender->getPhone(),
                ],
                'receiver' => [
                    'fullname' => [
                        $parcel->getRecipient()->getFullName()->getFullNameArray(),
                    ],
                    'phone' => $parcel->getRecipient()->getPhone(),
                ],
                'dimensions' => [
                    $parcel->getDimensions()->getDimensionsArray(),
                ],
                'estimatedCost' => $parcel->getEstimatedCost(),
            ];
        }

        return $result;
    }

    private function createNewSender(array $fullNameData, string $phone, string $address): Sender
    {
        $fullName = $this->fullNameService->addNewFullName($fullNameData);

        $sender = new Sender();
        $sender
            ->setFullName($fullName)
            ->setPhone($phone)
            ->setAddress($address)
        ;
        $this->senderRepo->save($sender, true);

        return $sender;
    }
}
