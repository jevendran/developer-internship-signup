<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $session = current_session(read_json());
    $profile = profile_for_user((string) $session['user_id']);

    if (!$profile) {
        json_response(['message' => 'Profile was not found.'], 404);
    }

    json_response(['profile' => $profile]);
} catch (Throwable $error) {
    json_response(['message' => 'Could not load profile.', 'error' => $error->getMessage()], 500);
}
