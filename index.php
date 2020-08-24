<?php
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

    $imeErr = $gesloErr = $error = "";
    $ime = $geslo = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["ime"])) {
            $imeErr = "Username is required! <br>";
        } else {
            $ime = test_input($_POST["ime"]);
            if (!preg_match("/^(?=[a-zA-Z0-9._]{5,14}$)(?!.*[_.]{2})[^_.].*[^_.]$/",$ime)) {
                $imeErr = "Invalid username! <br>";
                $ime = "";
            }
        }
            
        if (empty($_POST["geslo"])) {
            $gesloErr = "Password is required! ";
        } else {
            $geslo = test_input($_POST["geslo"]);
            if (!preg_match("/^(?=[a-zA-Z0-9._]{8,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/",$geslo)) {
                $gesloErr = "Invalid password!";
                $geslo = "";
            }
        }
    }

    $error = $imeErr . $gesloErr;

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToToe</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="icon" href="public/img/logo.png">
</head>
<body>  
    <div class="container">
        <div class="login">
            <p>Login</p><br>
            <form class="inside" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="POST">
                <input type="text" name="ime" placeholder="Username">
                <input type="password" name="geslo" placeholder="Password">
                <input type="submit" value="Login">
                <input type="reset" value="Clear">
            </form>
            <p>Haven't registered yet? <a href="register.php" class="bluer">Register!</a></p>
            <p style="color: white;"><?php echo $error; ?></p>

            <?php
            if((empty($error))&&(!empty($ime))&&(!empty($geslo))){
                echo "<form action=\"backend/login.php\" method=\"POST\" id=\"login\">
                    <input name=\"ime\" type=\"hidden\" value=\"$ime\">
                    <input name=\"geslo\" type=\"hidden\" value=\"$geslo\">
                    </form>";
            }
            ?>
            <script type="text/javascript">
                document.getElementById('login').submit(); // SUBMIT FORM
            </script>

        </div>
    </div>

<?php
    $conn = null;
    session_start();
    session_unset();
    session_destroy();
?>

</body>
</html>