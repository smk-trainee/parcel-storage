<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ParcelDto;
use App\Repository\ParcelRepository;
use App\Service\ParcelService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParcelSearchController extends AbstractController
{

    public function __construct(private readonly ParcelService $parcelService)
    {
    }

    #[OA\Response(
        response: 200,
        description: 'Parcel found successfully',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ParcelDto::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Parcel not found'
    )]
    #[OA\Response(
        response: 500,
        description: 'Search error'
    )]
    #[OA\Parameter(
        name: 'searchType',
        description: 'Поле используется для определения типа поиска. Допустимые значения sender_phone и receiver_fullname',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'q',
        description: 'Поле используется для поиска по заданному значению',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Parcel')]
    #[Route('/api/parcel', name: 'app_parcel_search', methods: 'GET')]
    public function __invoke(Request $request): JsonResponse
    {
        $validation = $this->parcelService->searchValidate($request);
        if ($validation) {
            return $this->json($validation, 500);
        }

        $searchType = $request->query->get('searchType');
        $query = $request->query->get('q');

        $response = $this->parcelService->search($searchType, $query);

        return $this->json($response, $response['code']);

    }
}
