<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\ApiRequest;

class StoreIpRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ip' => 'required|ip'
        ];
    }
}
