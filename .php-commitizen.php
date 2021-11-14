<?php

declare(strict_types=1);

return [
    'type' => [
        'lengthMin' => 3,
        'lengthMax' => 8,
        'acceptExtra' => false,
        'values' => [
            'feat',
            'fix',
            'docs',
            'chore',
            'test',
            'refactor',
            'revert',
            'ci',
        ]
    ],
    'scope' => [
        'lengthMin' => 0,
        'lengthMax' => 10,
        'acceptExtra' => true,
        'values' => [],
    ],
    'description' => [
        'lengthMin' => 1,
        'lengthMax' => 47,
    ],
    'subject' => [
        'lengthMin' => 1,
        'lengthMax' => 69,
    ],
    'body' => [
        'wrap' => 72,
    ],
    'footer' => [
        'wrap' => 72,
    ],
];
