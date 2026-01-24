<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCidr implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            [$ip, $prefix] = explode('/', $value);

            $isIpv4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
            $isIpv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

            if (! $isIpv4 && ! $isIpv6) {
                $fail("The {$attribute} field must be a valid ip.");
            }

            if ($isIpv4 && ($prefix < 0 || $prefix > 32)) {
                $fail("The {$attribute} field must be a valid prefix.");
            }

            if ($isIpv6 && ($prefix < 0 || $prefix > 128)) {
                $fail("The {$attribute} field must be a valid prefix.");
            }
        } catch (Exception $e) {
            $fail("The {$attribute} field must be a valid ip.");
        }
    }
}
