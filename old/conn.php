$servername = "localhost";
$username = "root";
$password = "";
$db = "todo";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    debug_to_console("Database connection success!");
} catch(PDOException $e) {
    $msg = 'Database connection failed: ' . $e->getMessage();
    $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
    debug_to_console($msg);
}