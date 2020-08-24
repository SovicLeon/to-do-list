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

    $imeErr = $geslo1Err = $geslo2Err = $emailErr = $error = "";
    $ime = $geslo1 = $geslo2 = $email = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (($_POST["geslo1"])==($_POST["geslo2"])) {
    
            if (empty($_POST["ime"])) {
                $imeErr = "Username is required! <br>";
            } else {
                $ime = test_input($_POST["ime"]);
                if (!preg_match("/^(?=[a-zA-Z0-9._]{5,14}$)(?!.*[_.]{2})[^_.].*[^_.]$/",$ime)) {
                    $imeErr = "Invalid username! <br>";
                    $ime = "";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "Email is required! <br>";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format <br>";
                  $email = "";
                }
            }
                
            if (empty($_POST["geslo1"])) {
                $geslo1Err = "Password is required! <br>";
            } else {
                $geslo = test_input($_POST["geslo1"]);
                if (!preg_match("/^(?=[a-zA-Z0-9._]{8,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/",$geslo)) {
                    $geslo1Err = "Invalid password! <br>";
                    $geslo = "";
                }
            }
        } else {
            $geslo2Err = "Passwords don't match! <br>";
        }
    }

    $error = $imeErr . $geslo1Err . $geslo2Err . $emailErr;

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
        <div class="login2">
            <p>Register</p><br>
            <form class="inside" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="POST">
                <input type="text" name="ime" placeholder="Userame">
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="geslo1" placeholder="Password">
                <input type="password" name="geslo2" placeholder="Confirm password">
                <input type="submit" value="Register">
                <input type="reset" value="Clear">
            </form>
            <p>Already registered? <a href="index.php" class="bluer">Login!</a></p>
            <p style="color: white;"><?php echo $error; ?></p>

            <?php
                if((empty($error))&&(!empty($ime))&&(!empty($geslo))&&(!empty($email))){
                    echo "<form action=\"backend/reg.php\" method=\"POST\" id=\"login\">
                        <input name=\"ime\" type=\"hidden\" value=\"$ime\">
                        <input name=\"email\" type=\"hidden\" value=\"$email\">
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