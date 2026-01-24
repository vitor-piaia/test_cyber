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
use Symfony\Component\HttpFoundation\Response;

class DeviceIpController extends Controller
{
    public function __construct(private readonly RegisterDeviceIp $registerDeviceIp) {}

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
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
