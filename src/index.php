<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Ramsey\Uuid\Uuid;

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path == '/status') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'UP']);
    exit;
} elseif ($path != '/') {
    // If the request is not to the root path, do not process further.
    http_response_code(404); // Optionally, you can set this to 404 or another appropriate code.
    echo "Not Found";
    exit;
}

// Database operations only occur if the request is to the root path "/"
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

echo <<<EOT
<pre>
This is a simple, basic PHP application running on <a href="https://zerops.io/">Zerops.io</a>,
serving the same content whether deployed on Apache or Nginx service.  
Each request adds an entry to the PostgreSQL database and returns a count.  

See the source repository (<a href="https://github.com/zeropsio/recipe-php">https://github.com/zeropsio/recipe-php</a>) for more information.


Entry added successfully with random data: $data. Total count: {$row['count']}

</pre>
EOT;

pg_close($dbconn);

syslog(LOG_INFO, "LOG_INFO  Entry added successfully with random data:" . $data);
syslog(LOG_NOTICE, "LOG_NOTICE  Entry added successfully with random data:" . $data);
syslog(LOG_DEBUG	, "LOG_DEBUG    Entry added successfully with random data:" . $data);

error_log("error_log    Entry added successfully with random data:" . $data);

phpinfo();