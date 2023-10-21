<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Dimensions as DimensionDto;
use App\Dto\ParcelDto;
use App\Entity\Address;
use App\Entity\Dimensions;
use App\Entity\FullName;
use App\Entity\Parcel;
use App\Entity\Recipient;
use App\Entity\Sender;
use App\Repository\ParcelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;

class ParcelService
{
    public function __construct(private ParcelRepository $parcelRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function search(?string $searchType, ?string $query): array
    {
        $result = [
            'code' => 404,
            'message' => 'Parcel not found'
        ];
        if (!empty($searchType) && !empty($query))
        {
            if ($searchType == "sender_phone")
            {
                $response = $this->parcelRepository->findBySenderPhone($query);
            } else {
                $response = $this->parcelRepository->findByRecipientFullName($query);
            }
            if ($response) {
                $result = [
                    'code' => 200,
                    'data' => $response
                ];
            }
        }
        return $result;
    }

    public function searchValidate(Request $request): ?array
    {
        define("App\Service\FULL_NAME_PATTERN", "/^\s*([a-zA-Z]+)\s+([a-zA-Z]+)\s+([a-zA-Z]+)$/");
        define("App\Service\PHONE_PATTERN", "/^\\+?[1-9][0-9]{7,14}$/");
        $response = null;

        $searchType = $request->query->get('searchType');
        $query = $request->query->get('q');

        $input = [
            'searchType' => $searchType,
            'query' => $query
        ];

        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
            'searchType' => [
                new Assert\Choice(['sender_phone', 'receiver_fullname']),
                new Assert\NotBlank(message: 'Введите тип поиска')
            ],
            'query' => [
                new Assert\NotBlank(message: 'Введите данные для поиска'),
                new Assert\Callback([
                    'callback' => function($query, ExecutionContextInterface $context) use ($searchType)
                    {
                        if (empty($query)) return;
                        if ($searchType == "receiver_fullname")
                        {
                            if (!preg_match(FULL_NAME_PATTERN, $query))
                            {
                                $context->buildViolation('Введите корректное ФИО')->addViolation();
                            }
                        }
                        elseif ($searchType == "sender_phone")
                        {
                            if (!preg_match(PHONE_PATTERN, $query))
                            {
                                $context->buildViolation('Введите корректный номер телефона')->addViolation();
                            }
                        }
                    }
                ]),
            ]
        ]);

        $violations = $validator->validate($input, $constraints);

        if (count($violations) > 0) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $errorMessages = [];

            foreach ($violations as $violation) {
                $accessor->setValue($errorMessages,
                    $violation->getPropertyPath(),
                    $violation->getMessage());
            }

            $response['message'] = $errorMessages;
        }

        return $response;

    }

    public function add(ParcelDto $dto): Parcel
    {
        $sender = $this->handleContactDto($dto->sender, "App\\Entity\\Sender");
        $recipient = $this->handleContactDto($dto->recipient, "App\\Entity\\Recipient");
        $dimensions = $this->handleValuationDto($dto->dimensions);
        $valuation = $dto->valuation;

        $parcel = new Parcel($sender, $recipient, $dimensions, $valuation);
        $this->parcelRepository->create($parcel);

        return $parcel;
    }

    private function handleContactDto($dto, string $class): Sender|Recipient
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Invalid entity class provided.');
        }

        $firstName = $dto->fullName->firstName;
        $lastName = $dto->fullName->lastName;
        $middleName = $dto->fullName->middleName;
        $city = $dto->address->city;
        $country = $dto->address->country;
        $street = $dto->address->street;
        $house = $dto->address->house;
        $apartment = $dto->address->apartment;

        $fullName = new FullName($firstName, $lastName, $middleName);
        $phone = $dto->phone;
        $address = new Address($city, $country, $street, $house, $apartment);

        return new $class($fullName, $phone, $address);
    }

    private function handleValuationDto(DimensionDto $dto): Dimensions
    {
        return new Dimensions($dto->weight, $dto->length, $dto->height, $dto->width);
    }

    /**
     * @throws Exception
     */
    public function delete(?int $id): bool
    {
        if (is_null($id)) throw new \InvalidArgumentException("Invalid parcel ID provided");

        $parcel = $this->parcelRepository->findOneBy(['id' => $id]);
        if (!$parcel) {
            throw new Exception(
                'No parcel found'
            );
        }
        $this->parcelRepository->destroy($parcel);
        return true;
    }


}
