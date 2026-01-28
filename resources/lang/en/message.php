<?php

return [
    'error' => [
        'default' => 'An error has occurred, please try again later.',
        'not_found' => 'No records found.',
        'ip_already_exists' => 'IP already exists.',
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
            'ip' => [
                'created' => 'IP device added successfully.',
            ]
        ],
        'network' => [
            'updated' => 'Network updated successfully.',
            'deleted' => 'Network deleted successfully.',
        ],
        'device_network_access' => [
            'refresh_metadata' => 'Your update is being processed.'
        ],
    ],
];
