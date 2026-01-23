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
use Symfony\Component\HttpFoundation\Response;

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
    public function show(int $networkId): NetworkResource|JsonResponse
    {
        try {
            $network = $this->networkService->show($networkId);

            return new NetworkResource($network, Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
                'message' => __('message.error.not-found'),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('message.error.default'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
