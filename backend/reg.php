<?php
    if ((isset($_POST["ime"]))&&(isset($_POST["email"]))&&(isset($_POST["geslo"]))){
        function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);
            echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
        }

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

        $ime = $_POST["ime"];
        $email = $_POST["email"];
        $geslo = $_POST["geslo"];
        
        $stmt = $conn->prepare("SELECT ime FROM uporabniki WHERE ime = '$ime'");
        $stmt->execute();

        $user = $stmt->fetch();
        $hash = $user[0];

        debug_to_console($hash);

        if (empty($hash)) {
            try{
                $options = [
                    'cost' => 8,
                ];
                $geslo = password_hash($geslo, PASSWORD_BCRYPT, $options);
                
                $sql = "INSERT INTO uporabniki (ime, enaslov, geslo)
                VALUES ('$ime', '$email', '$geslo')";
                $conn->exec($sql);
                debug_to_console("New DB log created!");
                $response = 'User created! <a href="../index.php" class="bluer">Login!</a>';
            } catch(PDOException $e) {
                $msg = 'Database connection failed: ' . $e->getMessage();
                $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
                debug_to_console($msg);
            }
        } else {
            $response = 'User already exists! <a href="../index.php" class="bluer">Login?</a>';
        }

        $conn = null;
    } else {
        header('location: ../register.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToToe</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body> 
    <div class="container">
        <div class="login">
            <?php echo $response; ?>
        </div>
    </div>

<?php
    session_start();
    session_unset();
    session_destroy();
?>

</body>
</html>