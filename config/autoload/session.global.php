<?php

use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;

return [
    // Session configuration.
    'session_config'  => [
        'save_handler'        => 'redis',
        'save_path'           => 'tcp://127.0.0.1:6379?weight=1&timeout=1&prefix=stats-session:',
        'cookie_lifetime'     => 60 * 60 * 24,  // Session cookie will expire in 24 hours.
        'gc_maxlifetime'      => 60 * 60 * 24,  // Store session data on server maximum for 24 hour.
        'cookie_path'         => '/',
        'cookie_secure'       => FALSE,
        //'remember_me_seconds' => 31536000,
        'remember_me_seconds' => 604800, // one week
        'use_cookies'         => TRUE,
        'cache_expire'        => 30,
        'name'                => 'stats-session',
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class,
    ],
];
