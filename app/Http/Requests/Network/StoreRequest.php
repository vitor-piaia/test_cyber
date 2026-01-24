<?php

namespace App\Http\Requests\Network;

use App\Http\Requests\ApiRequest;
use App\Rules\ValidCidr;

class StoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'cidr' => [
                'required',
                'string',
                'unique:networks,cidr',
                new ValidCidr
            ],
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
