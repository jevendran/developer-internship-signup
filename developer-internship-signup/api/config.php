<?php
declare(strict_types=1);

define('MYSQL_DSN', sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8mb4',
    getenv('MYSQL_HOST') ?: '127.0.0.1',
    getenv('MYSQL_DATABASE') ?: 'internship_signup'
));
define('MYSQL_USER', getenv('MYSQL_USER') ?: 'root');
define('MYSQL_PASS', getenv('MYSQL_PASSWORD') ?: '');

define('MONGO_URI', getenv('MONGO_URI') ?: 'mongodb://127.0.0.1:27017');
define('MONGO_DB', getenv('MONGO_DB') ?: 'internship_signup');
define('MONGO_USERS_COLLECTION', getenv('MONGO_USERS_COLLECTION') ?: 'registered_users');

define('REDIS_HOST', getenv('REDIS_HOST') ?: '127.0.0.1');
define('REDIS_PORT', (int) (getenv('REDIS_PORT') ?: 6379));
const SESSION_TTL_SECONDS = 86400;
