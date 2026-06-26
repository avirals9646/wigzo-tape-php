<?php
return [
    'db_host' => getenv('DB_HOST') ?: '127.0.0.1',
    'db_port' => getenv('DB_PORT') ?: '3306',
    'db_name' => getenv('DB_NAME') ?: 'wigzo_tape_db',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: '',
    'db_driver' => getenv('DB_DRIVER') ?: 'mysql',
    'sqlite_path' => getenv('SQLITE_PATH') ?: __DIR__ . '/../database/wigzo_tape.sqlite',
    'jwt_secret' => getenv('JWT_SECRET') ?: 'change-this-secret-key',
    'cors_origins' => getenv('CORS_ORIGINS') ?: '*',
    'razorpay_key_id' => getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_SnL4lcttenJnZV',
    'razorpay_key_secret' => getenv('RAZORPAY_KEY_SECRET') ?: 'HETOGQl1hwnqSwc9ooeJMIMh',
];
