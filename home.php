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
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        debug_to_console("Database connection success!");
    } catch(PDOException $e) {
        $msg = 'Database connection failed: ' . $e->getMessage();
        $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
        debug_to_console($msg);
    }

    session_start();
    if(!isset($_SESSION["user"])){
        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="to-do list">
    <meta name="keywords" content="to do, todo, to-do, list">
    <meta name="author" content="Leon Sovič">
    <title>ToToe</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="icon" href="public/img/logo.png">
</head>
<body>
    <header>
        <a id="icon" href="#"> <img src="public/img/icon.png" id="homeIcon" width="15%" height="90%"></a> <a id="ime" href="#"><?php echo $_SESSION["user"]; ?></a> 
    </header>
    <nav>
        <ul>
            <li><a class="button" href="home.php">Home</a></li>
            <li><a class="button" href="#">Projects</a></li>
            <li><a class="button" href="#">Logbook</a></li>
            <li><a class="button" href="#">Profile</a></li>
            <li><a class="button" href="index.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <form class="form" action="home.php" method="POST"><input class="inputtext" type="text" placeholder="Your task!" name ="vsebina"><input class="inputsub" type="submit" value="ADD" name="add"></form>

        <?php
            $id = $_SESSION["id"];
            try {
                //$stmt = $conn->prepare("SELECT * FROM opravila WHERE uporabnikID = '$id'");
                //$stmt->execute();
                //$user = $stmt->fetch();
                //$id = $user[0];
                $data = $conn->query("SELECT * FROM opravila WHERE uporabnikID = '$id'")->fetchAll(PDO::FETCH_UNIQUE);
            } catch(PDOException $e) {
                $msg = 'Database connection failed: ' . $e->getMessage();
                $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
                debug_to_console($msg);
            }
            if (!empty($data)) {
                for ($i=min(array_keys($data));$i<=max(array_keys($data));$i++) {
                    echo "<form class=\"form\" action=\"home.php\" method=\"POST\">" . "<input class=\"inputtext\" type=\"text\" value=\"" . $data[$i]["vsebina"] . "\" readonly> <input type=\"hidden\" name=\"id\" value=\"" . $i . "\"> <input class=\"inputsub\" type=\"submit\" value=\"X\" name=\"remove\"> </form>"; 
                }
            }
        ?>

    </main>

<?php
    if(isset($_POST['remove'])){
        $id= $_POST['id'];
        try{
            $sql = "DELETE FROM opravila WHERE ID=$id";
            $conn->exec($sql);
            $msg = "Delete!";
            debug_to_console($msg);
            header('location: home.php');
        } catch(PDOException $e) {
            $msg = 'Database connection failed: ' . $e->getMessage();
            $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
            debug_to_console($msg);
        }        
    }

    if(isset($_POST['add'])){
        $vsebina = $_POST["vsebina"];
        $date = date('Y-m-d H:i:s');
        try{
            $sql = "INSERT INTO opravila (uporabnikID, vsebina, ustvarjeno) VALUES ('$id', '$vsebina', '$date')";
            $conn->exec($sql);
            $msg = "New record created successfully";
            debug_to_console($msg);
            header('location: home.php');
        } catch(PDOException $e) {
            $msg = 'Database connection failed: ' . $e->getMessage();
            $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
            debug_to_console($msg);
        }
    }
    
    $conn = null;
?>

</body>
</html>