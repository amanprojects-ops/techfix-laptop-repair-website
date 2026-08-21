<?php

return [
    'name'    => $_ENV['APP_NAME']   ?? 'TechFix',
    'env'     => $_ENV['APP_ENV']    ?? 'production',
    'debug'   => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'secret'  => $_ENV['APP_SECRET'] ?? '',
    'url'     => $_ENV['APP_URL']    ?? 'http://localhost',
    'tracking_prefix' => $_ENV['TRACKING_PREFIX'] ?? 'AMN-LR',
    'upload_max_size' => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880),
    'timezone' => 'Asia/Kolkata',
];
