<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FullName;
use App\Entity\Parcel;
use App\Entity\Recipient;
use App\Repository\FullNameRepository;
use App\Repository\RecipientRepository;
use Doctrine\ORM\NoResultException;
use PHPUnit\Runner\Exception;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class RecipientService
{
    public function __construct(
        private readonly FullNameRepository $fullNameRepo,
        private readonly FullNameService $fullNameService,
        private readonly RecipientRepository $recipientRepo
    ) {
    }

    public function findOrCreate(array $data): Recipient
    {
        $fullNameData = $data['fullname'][0];
        try {
            $recipient = $this->fullNameRepo->findOneBy([
                'firstName' => $fullNameData['firstName'],
                'lastName' => $fullNameData['lastName'],
                'middleName' => $fullNameData['middleName'],
            ]);
            if ($recipient instanceof FullName) {
                return $recipient->getRecipients()->last();
            }
            $recipient = $this->createNewRecipient($fullNameData, $data['phone'], $data['address']);
        } catch (\Exception) {
            $recipient = $this->createNewRecipient($fullNameData, $data['phone'], $data['address']);
        }

        return $recipient;
    }

    /**
     * @throws \Exception
     */
    public function searchByFullName(array $fullName): array
    {
        $result = $this->fullNameRepo->findOneBy([
            'lastName' => $fullName['lastName'],
            'firstName' => $fullName['firstName'],
            'middleName' => $fullName['middleName'],
        ]);

        if (!$result instanceof FullName) {
            throw new NoResultException();
        }
        $recipient = $result->getRecipients()->last();

        if (!$recipient instanceof Recipient) {
            throw new Exception('no parcels for this user');
        }

        return $this->formatSearchData($recipient);
    }

    private function formatSearchData(Recipient $recipient): array
    {
        $parcels = $recipient->getParcels();
        if (!$parcels instanceof Parcel) {
            throw new InvalidTypeException('no parcels');
        }
        $result = [];

        /** @var Parcel $parcel */
        foreach ($parcels as $parcel) {
            $result[] = [
                'id' => $recipient->getId(),
                'sender' => [
                    'fullname' => [
                        $parcel->getSender()->getFullName()->getFullNameArray(),
                    ],
                    'phone' => $parcel->getSender()->getPhone(),
                ],
                'receiver' => [
                    'fullname' => [
                        $recipient->getFullName()->getFullNameArray(),
                    ],
                    'phone' => $recipient->getPhone(),
                ],
                'dimensions' => [
                    $parcel->getDimensions()->getDimensionsArray(),
                ],
                'estimatedCost' => $parcel->getEstimatedCost(),
            ];
        }

        return $result;
    }

    private function createNewRecipient(array $fullNameData, string $phone, string $address): Recipient
    {
        $fullName = $this->fullNameService->addNewFullName($fullNameData);

        $recipient = new Recipient();
        $recipient
            ->setFullName($fullName)
            ->setPhone($phone)
            ->setAddress($address);
        $this->recipientRepo->save($recipient, true);

        return $recipient;
    }
}
