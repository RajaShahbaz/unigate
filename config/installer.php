<?php

use Illuminate\Support\Facades\File;

return [
    'icon' => 'assets/horizontal-logo.svg',

    //    'background' => 'assets/logo.svg',

    'support_url' => 'https://join.skype.com/invite/ur5Wrt8RIAxJ',

    'server' => [
        'php'       => [
            'name'    => 'PHP Version',
            'version' => '>= 8.0.0',
            'check'   => 'php_version'
        ],
        'pdo'       => [
            'name'  => 'PDO',
            'check' => 'pdo_mysql'
        ],
        'mbstring'  => [
            'name'  => 'Mbstring extension',
            'check' => 'mbstring'
        ],
        'fileinfo'  => [
            'name'  => 'Fileinfo extension',
            'check' => 'fileinfo'
        ],
        'openssl'   => [
            'name'  => 'OpenSSL extension',
            'check' => 'openssl'
        ],
        'tokenizer' => [
            'name'  => 'Tokenizer extension',
            'check' => 'tokenizer'
        ],
        'json'      => [
            'name'  => 'Json extension',
            'check' => 'json'
        ],
        'curl'      => [
            'name'  => 'Curl extension',
            'check' => 'curl'
        ],
        'zip'       => [
            'name'  => 'Zip extension',
            'check' => 'zip'
        ]
    ],

    'folders' => [
        'storage.framework' => [
            'name'  => 'storage/framework',
            'check' => 'storage_framework'
        ],
        'storage.logs'      => [
            'name'  => 'storage/logs',
            'check' => 'storage_logs'
        ],
        'storage.cache'     => [
            'name'  => 'bootstrap/cache',
            'check' => 'bootstrap_cache'
        ],
    ],

    'database' => [
        'seeders' => false
    ],

    'commands' => [
        'db:seed --class=InstallationSeeder',
        'db:seed --class=AddSuperAdminSeeder',
    ],

    'admin_area' => [
        'user' => [
            'email'    => 'superadmin@gmail.com',
            'password' => 'superadmin'
        ]
    ]
];
