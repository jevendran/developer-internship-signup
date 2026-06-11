<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $data = read_json();
    $session = current_session($data);
    require_fields($data, ['age', 'dob', 'contact']);

    $age = filter_var($data['age'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 120],
    ]);
    if ($age === false) {
        json_response(['message' => 'Enter a valid age.'], 422);
    }

    $stmt = mysql()->prepare(
        'UPDATE user_profiles
         SET age = :age, dob = :dob, contact = :contact, city = :city, bio = :bio, updated_at = NOW()
         WHERE user_id = :user_id'
    );
    $stmt->execute([
        'age' => $age,
        'dob' => trim((string) $data['dob']),
        'contact' => trim((string) $data['contact']),
        'city' => trim((string) ($data['city'] ?? '')),
        'bio' => trim((string) ($data['bio'] ?? '')),
        'user_id' => (string) $session['user_id'],
    ]);

    $profile = profile_for_user((string) $session['user_id']);
    json_response(['message' => 'Profile updated successfully.', 'profile' => $profile]);
} catch (Throwable $error) {
    json_response(['message' => 'Could not update profile.', 'error' => $error->getMessage()], 500);
}
