<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ParcelDto;
use App\Enum\SearchTypeEnum;
use App\Handler\SearchHandler;
use Doctrine\ORM\NoResultException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcelSearchController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ParcelDto::class, groups: ['full']))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Return in case of not found',
    )]
    #[OA\Response(
        response: 400,
        description: 'Return in case of incompatible data',
    )]
    #[OA\Parameter(
        name: 'searchType',
        description: 'Поле используется для определения типа поиска.',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: SearchTypeEnum::CASES)
    )]
    #[OA\Parameter(
        name: 'q',
        description: 'Искомое значение',
        in: 'query',
        schema: new OA\Schema(
            type: 'string',
        )
    )]
    #[OA\Tag(name: 'Parcel')]
    #[Route('/api/parcel', name: 'app_parcel_search', methods: 'GET')]
    public function search(Request $request, SearchHandler $searchHandler): JsonResponse
    {
        $type = $request->get('searchType');
        $searchValue = $request->get('q');

        try {
            return $this->json([
                $searchHandler->handle($type, $searchValue),
            ]);
        } catch (NoResultException) {
            return $this->json([
                'message' => 'not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception|InvalidTypeException $e) {
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
