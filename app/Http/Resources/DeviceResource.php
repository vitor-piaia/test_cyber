<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class DeviceResource extends JsonResource
{
    public int $statusCode;

    public function __construct($resource, $statusCode = Response::HTTP_OK)
    {
        parent::__construct($resource);
        $this->statusCode = $statusCode;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'mac' => $this->mac,
            'device_type' => $this->device_type,
            'os' => $this->os,
            'status' => $this->status,
            'accesses' => $this->when($request->routeIs(['device.show', 'device.store']), DeviceNetworkAccessResource::collection($this->accesses)),
        ];
    }
}
