<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceNetworkAccess;
use App\Services\DeviceNetworkAccessService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class DeviceNetworkAccessController extends Controller
{
    public function __construct(private readonly DeviceNetworkAccessService $deviceNetworkAccessService) {}

    #[OA\Post(
        path: "/api/device-network-access/{accessId}/refresh-metadata",
        description: "Refresh device metadata",
        summary: "Refresh device metadata",
        tags: ["Metadata"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Refresh device metadata",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Your update is being processed.")
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
    public function refreshMetadata(DeviceNetworkAccess $access): JsonResponse
    {
        try {
            $this->deviceNetworkAccessService->refreshMetadata($access->id, $access->ip);

            return response()->json([
                'message' => __('message.success.device_network_access.refresh_metadata'),
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
