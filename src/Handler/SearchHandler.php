<?php

declare(strict_types=1);

namespace App\Handler;

use App\Enum\SearchTypeEnum;
use App\Service\RecipientService;
use App\Service\SenderService;

class SearchHandler
{
    public function __construct(
        private readonly RecipientService $recipientService,
        private readonly SenderService $senderService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function handle(string $searchType, string $searchValue): array
    {
        $type = SearchTypeEnum::tryFrom($searchType);
        if (!$type instanceof SearchTypeEnum) {
            throw new \Exception('undefined search type');
        }

        $searchValue = $this->validateSearchValue($type->value, $searchValue);

        return match ($type->value) {
            'sender_phone' => $this->senderService->searchByPhone($searchValue),
            'receiver_fullname' => $this->recipientService->searchByFullName($searchValue)
        };
    }

    /**
     * @throws \Exception
     */
    private function validateSearchValue(string $searchType, string $searchValue): string|array
    {
        return match ($searchType) {
            'sender_phone' => $this->validatePhone($searchValue),
            'receiver_fullname' => $this->validateFullName($searchValue)
        };
    }

    /**
     * @throws \Exception
     */
    private function validatePhone(string $phone): string
    {
        // телефон формата 9xxxxxxxxx
        if (preg_match('/^9[[:digit:]]{9}$/', $phone)) {
            return $phone;
        }
        throw new \Exception('invalid phone number');
    }

    /**
     * @throws \Exception
     */
    private function validateFullName(string $fullName): array
    {
        if (3 === str_word_count($fullName)) {
            $explode = explode(' ', $fullName);

            return [
                'firstName' => $explode[0],
                'lastName' => $explode[1],
                'middleName' => $explode[2],
            ];
        }
        throw new \Exception('Invalid full name. Use this template - FirstName LastName MiddleName');
    }
}
