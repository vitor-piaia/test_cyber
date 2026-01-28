<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Network\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Network\StoreRequest;
use App\Http\Requests\Network\UpdateRequest;
use App\Http\Resources\NetworkResource;
use App\Services\NetworkService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Info(
    version: "1.0.0",
    description: "Manages network infrastructures and their associated devices",
    title: "Test Cyber",
    contact: new OA\Contact(
        name: "Vitor Piaia",
        email: 'vitor.piaia@hotmail.com'
    ),
    x: [
        "logo" => [
            "url" => "https://via.placeholder.com/190x90.png?text=L5-Swagger"
        ]
    ],
)]
class NetworkController extends Controller
{
    public function __construct(private readonly NetworkService $networkService) {}

    public function list(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $orderBy = $request->get('order_by', 'asc');
            $perPage = $request->get('per_page', 15);
            $networks = $this->networkService->list($page, $orderBy, $perPage);

            return NetworkResource::collection($networks);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(
        path: "/api/networks/{networkId}",
        description: "Show network",
        summary: "Show network",
        tags: ["Networks"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail network",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Wifi"),
                        new OA\Property(property: "description", type: "string", example: "test network"),
                        new OA\Property(property: "cidr", type: "string", example: "0.0.0.0/24"),
                        new OA\Property(property: "location", type: "string", example: "Jundiaí"),
                        new OA\Property(property: "status", type: "string", example: "active"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "An error has occurred, please try again later")
                    ]
                )
            )
        ]
    )]
    public function show(int $networkId): NetworkResource|JsonResponse
    {
        try {
            $network = $this->networkService->show($networkId);

            return new NetworkResource($network, Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(
        path: "/api/networks",
        description: "Store network",
        summary: "Store network",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "description", "cidr", "location", "status"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Wifi House"),
                    new OA\Property(property: "description", type: "string", example: "Wifi"),
                    new OA\Property(property: "cidr", type: "string", example: "8.8.8.8/24"),
                    new OA\Property(property: "location", type: "string", example: "Jundiaí"),
                    new OA\Property(property: "status", type: "string", example: "active"),
                ]
            )
        ),
        tags: ["Networks"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Network created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Wifi"),
                        new OA\Property(property: "description", type: "string", example: "test network"),
                        new OA\Property(property: "cidr", type: "string", example: "0.0.0.0/24"),
                        new OA\Property(property: "location", type: "string", example: "Jundiaí"),
                        new OA\Property(property: "status", type: "string", example: "active"),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The name field must be a string.")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "An error has occurred, please try again later")
                    ]
                )
            )
        ]
    )]
    public function store(StoreRequest $request): NetworkResource|JsonResponse
    {
        try {
            $network = $this->networkService->store($request->validated());

            return new NetworkResource($network, Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(
        path: "/api/networks/{networkId}",
        description: "Update network",
        summary: "Update network",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "description", "cidr", "location", "status"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Wifi House"),
                    new OA\Property(property: "description", type: "string", example: "Wifi"),
                    new OA\Property(property: "cidr", type: "string", example: "8.8.8.8/24"),
                    new OA\Property(property: "location", type: "string", example: "Jundiaí"),
                    new OA\Property(property: "status", type: "string", example: "active"),
                ]
            )
        ),
        tags: ["Networks"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Network updated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Network updated successfully.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The name field must be a string.")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "An error has occurred, please try again later")
                    ]
                )
            )
        ]
    )]
    public function update(int $networkId, UpdateRequest $request): NetworkResource|JsonResponse
    {
        try {
            $this->networkService->update($networkId, $request->validated());

            return response()->json([
                'message' => __('message.success.network.updated'),
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(
        path: "/api/networks/{networkId}",
        description: "Delete network",
        summary: "Delete network",
        tags: ["Networks"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Network deleted",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Network deleted successfully.")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "No records found.")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "An error has occurred, please try again later")
                    ]
                )
            )
        ]
    )]
    public function delete(int $networkId): NetworkResource|JsonResponse
    {
        try {
            $this->networkService->delete($networkId);

            return response()->json([
                'message' => __('message.success.network.deleted'),
            ]);
        } catch (NotFoundException $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.not_found'),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
