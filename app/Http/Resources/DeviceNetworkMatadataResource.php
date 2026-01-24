<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class DeviceNetworkMatadataResource extends JsonResource
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
            'isp' => $this->isp,
            'domains' => $this->domains,
            'hostnames' => $this->hostnames,
            'geolocation' => $this->geolocation,
            'ports' => $this->ports,
            'last_shodan_scan_at' => $this->last_shodan_scan_at,
        ];
    }
}
