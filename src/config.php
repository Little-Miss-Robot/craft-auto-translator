<?php

return [
    'enabled' => true,
    'service' => 'simple',
    'services' => [
        'simple' => [
            'class' => \Lmr\AutoTranslator\Services\SimpleTranslationService::class,
        ],
    ],
    'policy' => [
        'class' => \Lmr\AutoTranslator\Policies\DefaultPolicy::class,
    ],
    'translate' => [
        'pages' => [
            'title',
        ],
    ],
];
