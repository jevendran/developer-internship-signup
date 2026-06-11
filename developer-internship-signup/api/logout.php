<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$data = read_json();
$token = trim((string) ($data['token'] ?? ''));

if ($token !== '') {
    redis_client()->del('session:' . $token);
}

json_response(['message' => 'Logged out successfully.']);
