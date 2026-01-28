<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Device\NotFoundException;
use App\Exceptions\Network\NotFoundException as NetworkNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\StoreRequest;
use App\Http\Requests\Device\UpdateRequest;
use App\Http\Resources\DeviceResource;
use App\Services\DeviceService;
use App\Services\Orchestrator\RegisterDeviceWithAccess;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    public function __construct(
        private readonly DeviceService $deviceService,
        private readonly RegisterDeviceWithAccess $registerDeviceWithAccess,
    ) {}

    public function list(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $orderBy = $request->get('order_by', 'asc');
            $perPage = $request->get('per_page', 15);
            $devices = $this->deviceService->list($page, $orderBy, $perPage);

            return DeviceResource::collection($devices);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(
        path: "/api/devices/{deviceId}",
        description: "Show device",
        summary: "Show device",
        tags: ["Devices"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail device",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Iphone"),
                        new OA\Property(property: "description", type: "string", example: "test device"),
                        new OA\Property(property: "mac", type: "string", example: "a0:00:a0:0a:0a:00"),
                        new OA\Property(property: "device_type", type: "string", example: "Cell phone"),
                        new OA\Property(property: "os", type: "string", example: "IOS"),
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
    public function show(int $deviceId): DeviceResource|JsonResponse
    {
        try {
            $device = $this->deviceService->show($deviceId);

            return new DeviceResource($device, Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(
        path: "/api/devices",
        description: "Store device",
        summary: "Store device",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "description", "cidr", "location", "status"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Iphone"),
                    new OA\Property(property: "description", type: "string", example: "Iphone"),
                    new OA\Property(property: "mac", type: "string", example: "a0:00:a0:0a:0a:00"),
                    new OA\Property(property: "device_type", type: "string", example: "Cell phone"),
                    new OA\Property(property: "os", type: "string", example: "IOS"),
                    new OA\Property(property: "status", type: "string", example: "active"),
                    new OA\Property(property: "ip", type: "string", example: "0.0.0.0"),
                ]
            )
        ),
        tags: ["Devices"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Network created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Iphone"),
                        new OA\Property(property: "description", type: "string", example: "test device"),
                        new OA\Property(property: "mac", type: "string", example: "a0:00:a0:0a:0a:00"),
                        new OA\Property(property: "device_type", type: "string", example: "Cell phone"),
                        new OA\Property(property: "os", type: "string", example: "IOS"),
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
    public function store(StoreRequest $request): DeviceResource|JsonResponse
    {
        try {
            $device = $this->registerDeviceWithAccess->execute($request->validated());

            return new DeviceResource($device, Response::HTTP_CREATED);
        } catch (NetworkNotFoundException $e) {
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

    #[OA\Put(
        path: "/api/devices/{deviceId}",
        description: "Update device",
        summary: "Update device",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "description", "cidr", "location", "status"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Iphone"),
                    new OA\Property(property: "description", type: "string", example: "Iphone"),
                    new OA\Property(property: "mac", type: "string", example: "a0:00:a0:0a:0a:00"),
                    new OA\Property(property: "device_type", type: "string", example: "Cell phone"),
                    new OA\Property(property: "os", type: "string", example: "IOS"),
                    new OA\Property(property: "status", type: "string", example: "active"),
                    new OA\Property(property: "ip", type: "string", example: "0.0.0.0"),
                ]
            )
        ),
        tags: ["Devices"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Device updated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Device updated successfully.")
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
    public function update(int $deviceId, UpdateRequest $request): DeviceResource|JsonResponse
    {
        try {
            $this->deviceService->update($deviceId, $request->validated());

            return response()->json([
                'message' => __('message.success.device.updated'),
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(
        path: "/api/devices/{deviceId}",
        description: "Delete device",
        summary: "Delete device",
        tags: ["Devices"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Device deleted",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Device deleted successfully.")
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
    public function delete(int $deviceId): DeviceResource|JsonResponse
    {
        try {
            $this->deviceService->delete($deviceId);

            return response()->json([
                'message' => __('message.success.device.deleted'),
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
