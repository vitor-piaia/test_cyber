<?php

namespace App\Http\Requests\Network;

use App\Http\Requests\ApiRequest;
use App\Rules\ValidCidr;
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'cidr' => [
                'required',
                'string',
                new ValidCidr,
                Rule::unique('networks', 'cidr')->ignore($id)
            ],
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
