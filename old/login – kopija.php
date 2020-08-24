<?php
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
?>

<?php
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

    //$ime = $_POST["ime"];

    $options = [
        'cost' => 8,
    ];
    $hash = password_hash("Leonqsovic123", PASSWORD_BCRYPT, $options);

    echo $hash . "<br> <br>";
    
    $stmt = $conn->prepare("SELECT geslo FROM uporabniki WHERE ime = 'LeonSovic'");
    $stmt->execute();

    $user = $stmt->fetch();

    echo "<h1>" . $user[0] . "</h1>";

    if(password_verify('Leonqsovic123', $user[0])){
        echo 'Prav je!';
    }
    else{
        echo 'Nemrem!!!!11!';
    }


?>
    
</body>
</html>

<?php
    $conn = null;
    ?>
