<?php

namespace App\Http\Requests\Network;

use App\Http\Requests\ApiRequest;

class StoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:networks,name',
            'description' => 'required|string|max:255',
            'network_range_start' => 'required|string|ip',
            'network_range_end' => 'required|string|ip',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
