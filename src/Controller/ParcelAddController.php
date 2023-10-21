<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ParcelDto;
use App\Service\ParcelService;
use App\Service\ValidationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ParcelAddController extends AbstractController
{
    public function __construct(private readonly ParcelService $parcelService)
    {
    }

    #[OA\Response(
        response: 201,
        description: 'Parcel added successfully',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ParcelDto::class))
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Parcel request error'
    )]
    #[OA\Response(
        response: 500,
        description: 'Parcel add error'
    )]
    #[OA\RequestBody(
        description: 'Данные для заполнения',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'sender',
                    properties: [
                        new OA\Property(property: 'fullName',
                            properties: [
                                new OA\Property(property: 'firstName', type: 'string'),
                                new OA\Property(property: 'lastName', type: 'string'),
                                new OA\Property(property: 'middleName', type: 'string'),
                            ]
                        ),
                        new OA\Property(property: 'phone', type: 'string'),
                        new OA\Property(property: 'address',
                            properties: [
                                new OA\Property(property: 'country', type: 'string'),
                                new OA\Property(property: 'city', type: 'string'),
                                new OA\Property(property: 'street', type: 'string'),
                                new OA\Property(property: 'house', type: 'integer'),
                                new OA\Property(property: 'apartment', type: 'integer'),
                            ]
                        ),
                    ]
                ),
                new OA\Property(property: 'recipient',
                    properties: [
                        new OA\Property(property: 'fullName',
                            properties: [
                                new OA\Property(property: 'firstName', type: 'string'),
                                new OA\Property(property: 'lastName', type: 'string'),
                                new OA\Property(property: 'middleName', type: 'string'),
                            ]
                        ),
                        new OA\Property(property: 'phone', type: 'string'),
                        new OA\Property(property: 'address',
                            properties: [
                                new OA\Property(property: 'country', type: 'string'),
                                new OA\Property(property: 'city', type: 'string'),
                                new OA\Property(property: 'street', type: 'string'),
                                new OA\Property(property: 'house', type: 'integer'),
                                new OA\Property(property: 'apartment', type: 'integer'),
                            ]
                        ),
                    ]
                ),
                new OA\Property(property: 'dimensions',
                    properties: [
                        new OA\Property(property: 'weight', type: 'integer'),
                        new OA\Property(property: 'length', type: 'integer'),
                        new OA\Property(property: 'height', type: 'integer'),
                        new OA\Property(property: 'width', type: 'integer'),
                    ]
                ),
                new OA\Property(property: 'valuation', type: 'integer'),
            ]
        ),
    )]
    #[OA\Tag(name: 'Parcel')]
    #[Route('/api/parcel', name: 'api_parcel_add', methods: 'POST')]
    public function __invoke(#[MapRequestPayload] ParcelDto $parcelDto, ValidationService $validationService): JsonResponse
    {
        try {
            $validationService->validate($parcelDto);
        } catch (ValidationFailedException $e) {
            $violations = $e->getViolations();
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = [
                    'propertyPath' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            return $this->json(['errors' => $errors], 422);
        }

        return $this->json([
            'message' => 'ok',
            'data' => $this->parcelService->add($parcelDto)
        ], 201);
    }
}
