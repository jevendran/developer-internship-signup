<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $data = read_json();
    require_fields($data, ['email', 'password']);

    $email = strtolower(trim((string) $data['email']));
    $user = find_user_by_email($email);

    if (!$user || !password_verify((string) $data['password'], (string) $user['password_hash'])) {
        json_response(['message' => 'Invalid email or password.'], 401);
    }

    $token = bin2hex(random_bytes(32));
    $session = [
        'user_id' => (string) $user['_id']['$oid'],
        'email' => $email,
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'logged_in_at' => time(),
    ];

    redis_client()->setex('session:' . $token, SESSION_TTL_SECONDS, json_encode($session));

    json_response([
        'message' => 'Login successful.',
        'token' => $token,
        'user' => [
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $email,
        ],
    ]);
} catch (Throwable $error) {
    json_response(['message' => 'Login failed.', 'error' => $error->getMessage()], 500);
}
