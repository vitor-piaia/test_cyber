<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class DeviceNetworkAccessResource extends JsonResource
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
            'ip' => $this->ip,
            'network_name' => $this->network?->name,
            'accessed_at' => $this->accessed_at,
            'disconnected_at' => $this->disconnected_at,
            'metadata' => new DeviceNetworkMatadataResource($this->metadata),
        ];
    }
}
