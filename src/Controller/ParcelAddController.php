<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ParcelDto;
use App\Service\ParcelService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcelAddController extends AbstractController
{
    #[OA\Tag(name: 'Parcel')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(ref: new Model(type: ParcelDto::class, groups: ['full']))
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the success of create new parcel',
    )]
    #[OA\Response(
        response: 400,
        description: 'Returns in case of invalid incoming data',
    )]
    #[Route('/api/parcel', name: 'api_parcel_add', methods: 'POST')]
    public function add(Request $request, ParcelService $parcelService): JsonResponse
    {
        $data = $request->getContent();

        try {
            return $this->json([
                'message' => $parcelService->addNewParcel($data),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
