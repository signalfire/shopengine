<?php

return [
    'product' => [
        'status' => [
            'UNAVAILABLE' => 0,
            'AVAILABLE'   => 1,
        ],
    ],
    'variant' => [
        'status' => [
            'UNAVAILABLE' => 0,
            'AVAILABLE'   => 1,
        ],
        'stock' => [
            'MIN' => 1,
        ],
    ],
    'category' => [
        'status' => [
            'UNAVAILABLE' => 0,
            'AVAILABLE'   => 1,
        ],
    ],
    'order' => [
        'status' => [
            'PROCESSING' => 1,
            'DISPATCHED' => 2,
        ],
        'emails' => [
            'canned' => [
                'POSTAL UPGRADE' => 'emails.#type#.upgrade',
            ],
        ],
    ],
];
