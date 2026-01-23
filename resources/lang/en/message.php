<?php

return [
    'error' => [
        'default' => 'An error has occurred, please try again later.',
        'not-found' => 'No records found.',
        'device' => [
            'validation' => [
                'mac' => [
                    'regex' => 'The MAC address is not valid. Use XX:XX:XX:XX:XX:XX.',
                ]
            ]
        ],
    ],
    'success' => [
        'device' => [
            'updated' => 'Device updated successfully.',
            'deleted' => 'Device deleted successfully.',
        ],
        'network' => [
            'updated' => 'Network updated successfully.',
            'deleted' => 'Network deleted successfully.',
        ],
    ],
];
