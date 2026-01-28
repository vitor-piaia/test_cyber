<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Device\IpAlreadyExistsException;
use App\Exceptions\Device\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\StoreIpRequest;
use App\Services\Orchestrator\RegisterDeviceIp;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class DeviceIpController extends Controller
{
    public function __construct(private readonly RegisterDeviceIp $registerDeviceIp) {}

    #[OA\Post(
        path: "/api/devices/{deviceId}/ip",
        description: "Store new IP device",
        summary: "Store new IP device",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["ip"],
                properties: [
                    new OA\Property(property: "ip", type: "string", example: "0.0.0.0"),
                ]
            )
        ),
        tags: ["Devices IP"],
        responses: [
            new OA\Response(
                response: 200,
                description: "IP device created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "IP device added successfully.")
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
                response: 409,
                description: "Conflict",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "IP already exists.")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Error validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "An error has occurred, please try again later.")
                    ]
                )

            )
        ]
    )]
    public function store(int $deviceId, StoreIpRequest $request): JsonResponse
    {
        try {
            $this->registerDeviceIp->execute($deviceId, $request->get('ip'));

            return response()->json([
                'message' => __('message.success.device.ip.created'),
            ], Response::HTTP_CREATED);
        } catch (NotFoundException $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.not_found'),
            ], Response::HTTP_NOT_FOUND);
        } catch (IpAlreadyExistsException $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.ip_already_exists'),
            ], Response::HTTP_CONFLICT);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
