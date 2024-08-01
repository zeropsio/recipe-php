<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Ramsey\Uuid\Uuid;

header('Content-Type: application/json');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Handle health check
if ($path == '/status') {
    echo json_encode(['status' => 'UP']);
    exit;
}

// Proceed only if the path is the root index ('/')
if ($path != '/') {
    exit;
}


if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$dbconn = pg_connect(
  "host=" . (getenv('DB_HOST') ?: 'localhost') . " " .
  "port=" . (getenv('DB_PORT') ?: '5432') . " " .
  "dbname=" . (getenv('DB_NAME') ?: 'mydatabase') . " " .
  "user=" . (getenv('DB_USER') ?: 'myuser') . " " .
  "password=" . (getenv('DB_PASS') ?: 'mypassword')
) or die('Could not connect: ' . pg_last_error());

pg_query($dbconn, "CREATE TABLE IF NOT EXISTS entries (id SERIAL PRIMARY KEY, data TEXT NOT NULL);");

$data = Uuid::uuid4()->toString();
pg_query($dbconn, "INSERT INTO entries (data) VALUES ('$data');");

$result = pg_query($dbconn, "SELECT COUNT(*) AS count FROM entries;");
$row = pg_fetch_assoc($result);

echo json_encode([
    "message" => "This is a simple, basic PHP application running on Zerops.io, serving the same content whether deployed on Apache or Nginx service. Each request adds an entry to the PostgreSQL database and returns a count. See the source repository (https://github.com/zeropsio/recipe-php) for more information.",
    "newEntry" => $data,
    "count" => $row['count']
]);

pg_close($dbconn);

error_log("error_log    Entry added successfully with random data:" . $data);

syslog(LOG_INFO, "LOG_INFO  Entry added successfully with random data:" . $data);
syslog(LOG_NOTICE, "LOG_NOTICE  Entry added successfully with random data:" . $data);
syslog(LOG_DEBUG, "LOG_DEBUG    Entry added successfully with random data:" . $data);

trigger_error("trigger_error - A user requested a resource.", E_USER_NOTICE);
trigger_error("trigger_error - The image failed to load!", E_USER_WARNING);
trigger_error("trigger_error - User requested a profile that doesn't exist!", E_USER_ERROR);
