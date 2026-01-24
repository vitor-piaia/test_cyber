<?php

return [
    'base_url' => env('SHODAN_BASE_URL', 'https://api.shodan.io'),
    'api_key'  => env('SHODAN_API_KEY'),
    'timeout'  => env('SHODAN_TIMEOUT', 10),
];
