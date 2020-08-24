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

    $ime = $_POST["ime"];
    $geslo = $_POST["geslo"];
    
    $stmt = $conn->prepare("SELECT geslo FROM uporabniki WHERE ime = '$ime'");
    $stmt->execute();

    $user = $stmt->fetch();
    $hash = $user[0];

    if (password_verify($geslo, $hash)) {
        debug_to_console("Logged in!");
        session_start();
        $_SESSION["user"] = $ime;

        $stmt = $conn->prepare("SELECT id FROM uporabniki WHERE ime = '$ime'");
        $stmt->execute();
        $user = $stmt->fetch();
        $id = $user[0];

        $_SESSION["id"] = $id;

        header('location: ../home.php');
    } else {
        debug_to_console("Fail login!");
        header('location: ../index.php');
    }

    $conn = null;
?>