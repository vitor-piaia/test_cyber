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
