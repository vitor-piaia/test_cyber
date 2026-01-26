<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('mac')) {
            $this->merge([
                'mac' => strtoupper(str_replace('-', ':', $this->mac)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'mac' => [
                'required',
                'string',
                Rule::unique('devices')
                    ->where(fn ($q) =>
                    $q->where('mac', $this->value)
                        ->whereNull('deleted_at')
                    ),
                'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/'
            ],
            'device_type' => 'required|string|max:50',
            'os' => 'required|string|max:50',
            'status' => 'required|string|in:active,inactive',
            'ip' => 'required|ip'
        ];
    }

    public function messages(): array
    {
        return [
            'mac.regex' => __('message.error.device.validation.mac.regex'),
        ];
    }
}
