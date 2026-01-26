<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceNetworkAccess;
use App\Services\DeviceNetworkAccessService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DeviceNetworkAccessController extends Controller
{
    public function __construct(private readonly DeviceNetworkAccessService $deviceNetworkAccessService) {}

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
