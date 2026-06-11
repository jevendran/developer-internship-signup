<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $data = read_json();
    require_fields($data, ['first_name', 'last_name', 'email', 'password', 'confirm_password']);

    $email = strtolower(trim((string) $data['email']));
    $password = (string) $data['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_response(['message' => 'Enter a valid email address.'], 422);
    }

    if (strlen($password) < 8) {
        json_response(['message' => 'Password must be at least 8 characters.'], 422);
    }

    if ($password !== (string) $data['confirm_password']) {
        json_response(['message' => 'Passwords do not match.'], 422);
    }

    if (find_user_by_email($email)) {
        json_response(['message' => 'This email is already registered.'], 409);
    }

    $firstName = trim((string) $data['first_name']);
    $lastName = trim((string) $data['last_name']);
    $userId = insert_registered_user([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => new MongoDB\BSON\UTCDateTime(),
    ]);

    $stmt = mysql()->prepare(
        'INSERT INTO user_profiles (user_id, first_name, last_name, email, created_at, updated_at)
         VALUES (:user_id, :first_name, :last_name, :email, NOW(), NOW())'
    );
    $stmt->execute([
        'user_id' => $userId,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
    ]);

    json_response(['message' => 'Registration successful. Please login.'], 201);
} catch (Throwable $error) {
    json_response(['message' => 'Registration failed.', 'error' => $error->getMessage()], 500);
}
