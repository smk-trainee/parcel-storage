<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ParcelService;
use Exception;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParcelDeleteController extends AbstractController
{
    public function __construct(private readonly ParcelService $parcelService)
    {
    }

    /**
     * @throws Exception
     */
    #[OA\Parameter(
        name: 'id',
        description: 'ID посылки',
        in: 'query',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 204,
        description: 'Success deleted',
    )]
    #[OA\Response(
        response: 422,
        description: 'Delete error',
    )]
    #[OA\Tag(name: 'Parcel')]
    #[Route('/api/parcel', name: 'app_parcel_delete', methods: 'DELETE')]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $this->parcelService->delete($id);

        return new JsonResponse([
            'message' => 'ok',
            'id' => $id
        ]);
    }
}
