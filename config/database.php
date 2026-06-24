<?php
/**
 * PDO database connection (singleton).
 */
require_once __DIR__ . '/config.php';

/**
 * Inject a custom PDO instance (used by the smoke-test harness).
 */
function db_set(PDO $pdo): void
{
    $GLOBALS['__app_pdo'] = $pdo;
}

function db(): PDO
{
    if (isset($GLOBALS['__app_pdo']) && $GLOBALS['__app_pdo'] instanceof PDO) {
        return $GLOBALS['__app_pdo'];
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $GLOBALS['__app_pdo'] = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        http_response_code(500);
        die('Database connection failed. Please check config/config.php credentials and that the database has been imported.');
    }
    return $GLOBALS['__app_pdo'];
}
