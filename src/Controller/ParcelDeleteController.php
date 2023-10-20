<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ParcelService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcelDeleteController extends AbstractController
{
    #[OA\Tag(name: 'Parcel')]
    #[OA\Parameter(
        name: 'parcelId',
        description: 'Id номер посылки',
        in: 'query',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 204,
        description: 'Returns the success of delete parcel',
    )]
    #[OA\Response(
        response: 422,
        description: 'Returns in case of unexciting parcel',
    )]
    #[Route('/api/parcel', name: 'app_parcel_delete', methods: 'DELETE')]
    public function delete(Request $request, ParcelService $service): JsonResponse
    {
        $id = $request->get('parcelId');

        if (!preg_match('/^[1-9][0-9]*$/', $id)) {
            return $this->json([
                'message' => 'undefined data type',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->json([
                'message' => $service->delete($id),
            ], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\TypeError) {
            return $this->json([
                'message' => 'undefined data type',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
