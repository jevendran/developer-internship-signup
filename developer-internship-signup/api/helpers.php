<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function read_json(): array
{
    $payload = json_decode(file_get_contents('php://input'), true);
    return is_array($payload) ? $payload : [];
}

function require_fields(array $data, array $fields): void
{
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
            json_response(['message' => 'Missing required field: ' . $field], 422);
        }
    }
}

function mysql(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = new PDO(MYSQL_DSN, MYSQL_USER, MYSQL_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function mongo_manager(): MongoDB\Driver\Manager
{
    if (!extension_loaded('mongodb')) {
        json_response(['message' => 'MongoDB PHP extension is not installed.'], 500);
    }

    static $manager = null;
    if ($manager instanceof MongoDB\Driver\Manager) {
        return $manager;
    }

    $manager = new MongoDB\Driver\Manager(MONGO_URI);
    return $manager;
}

function redis_client(): Redis
{
    if (!extension_loaded('redis')) {
        json_response(['message' => 'Redis PHP extension is not installed.'], 500);
    }

    static $redis = null;
    if ($redis instanceof Redis) {
        return $redis;
    }

    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    return $redis;
}

function find_user_by_email(string $email): ?array
{
    $query = new MongoDB\Driver\Query(['email' => strtolower($email)], ['limit' => 1]);
    $cursor = mongo_manager()->executeQuery(MONGO_DB . '.' . MONGO_USERS_COLLECTION, $query);
    $user = current($cursor->toArray());
    return $user ? json_decode(json_encode($user), true) : null;
}

function insert_registered_user(array $user): string
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $id = new MongoDB\BSON\ObjectId();
    $user['_id'] = $id;
    $bulk->insert($user);
    mongo_manager()->executeBulkWrite(MONGO_DB . '.' . MONGO_USERS_COLLECTION, $bulk);
    return (string) $id;
}

function current_session(array $data): array
{
    $token = trim((string) ($data['token'] ?? ''));
    if ($token === '') {
        json_response(['message' => 'Login token is required.'], 401);
    }

    $sessionJson = redis_client()->get('session:' . $token);
    if (!$sessionJson) {
        json_response(['message' => 'Session expired. Please login again.'], 401);
    }

    $session = json_decode($sessionJson, true);
    if (!is_array($session)) {
        json_response(['message' => 'Invalid session. Please login again.'], 401);
    }

    redis_client()->expire('session:' . $token, SESSION_TTL_SECONDS);
    return $session;
}

function profile_for_user(string $userId): ?array
{
    $stmt = mysql()->prepare('SELECT * FROM user_profiles WHERE user_id = :user_id LIMIT 1');
    $stmt->execute(['user_id' => $userId]);
    $profile = $stmt->fetch();
    return $profile ?: null;
}
