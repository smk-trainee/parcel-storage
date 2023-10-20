<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Parcel;
use App\Repository\ParcelRepository;

class ParcelService
{
    public function __construct(
        private readonly ParcelRepository $parcelRepo,
        private readonly SenderService $senderService,
        private readonly RecipientService $recipientService,
        private readonly DimensionsService $dimensionsService
    ) {
    }

    /**
     * @throws \Exception
     */
    public function addNewParcel(string $rawData): string
    {
        $parcelData = json_decode($rawData, true);
        if (null === $parcelData) {
            throw new \Exception('invalid data');
        }

        try {
            $senderData = $parcelData['sender'];
            $receiverData = $parcelData['receiver'];
            $dimensionsData = $parcelData['dimensions'][0];
        } catch (\Exception) {
            return 'invalid data map';
        }

        $sender = $this->senderService->findOrCreate($senderData);
        $recipient = $this->recipientService->findOrCreate($receiverData);
        $dimensions = $this->dimensionsService->findOrCreate($dimensionsData);

        $parcel = new Parcel();
        $parcel
            ->setRecipient($recipient)
            ->setSender($sender)
            ->setDimensions($dimensions)
            ->setEstimatedCost($parcelData['estimatedCost']);
        $this->parcelRepo->save($parcel, true);

        return 'ok';
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id): string
    {
        $parcel = $this->parcelRepo->findOneBy(['id' => $id]);

        if (!$parcel instanceof Parcel) {
            throw new \Exception('undefined parcel');
        }

        $this->parcelRepo->remove($parcel);

        return 'ok';
    }
}
