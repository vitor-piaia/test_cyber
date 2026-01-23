<?php

namespace App\Http\Requests\Network;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('networkId');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('networks', 'name')->ignore($id)
            ],
            'description' => 'required|string|max:255',
            'network_range_start' => 'required|string|ip',
            'network_range_end' => 'required|string|ip',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
