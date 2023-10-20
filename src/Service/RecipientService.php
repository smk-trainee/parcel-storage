<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FullName;
use App\Entity\Parcel;
use App\Entity\Recipient;
use App\Interfaces\FindOrCreateInterface;
use App\Repository\FullNameRepository;
use App\Repository\RecipientRepository;
use Doctrine\ORM\NoResultException;
use PHPUnit\Runner\Exception;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class RecipientService implements FindOrCreateInterface
{
    public function __construct(
        private readonly FullNameRepository $fullNameRepo,
        private readonly FullNameService $fullNameService,
        private readonly RecipientRepository $recipientRepo
    ) {
    }

    public function findOrCreate(array $data): Recipient
    {
        $fullNameData = $data['fullName'];
        if (!\array_key_exists('phone', $data) || !\array_key_exists('address', $data)) {
            throw new \Exception('missed required data');
        }
        try {
            $recipient = $this->fullNameRepo->findOneBy([
                'firstName' => $fullNameData['firstName'],
                'lastName' => $fullNameData['lastName'],
                'middleName' => $fullNameData['middleName'],
            ]);
            if ($recipient instanceof FullName && 0 !== $recipient->getRecipients()->count()) {
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
        if (null === $recipient->getParcels() || 0 === $recipient->getParcels()->count()) {
            throw new InvalidTypeException('no parcels');
        }
        $parcels = $recipient->getParcels();
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
