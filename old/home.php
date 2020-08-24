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


<?php
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
    <title>ToToe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a id="icon" href="#"> <img src="slike/icon.png" id="homeIcon" width="15%" height="90%"></a> <a id="ime" href="#"><?php echo $_SESSION["user"]; ?></a> 
    </header>
    <nav>
        <ul>
            <li><a class="button" href="#">Home</a></li>
            <li><a class="button" href="#">Projects</a></li>
            <li><a class="button" href="#">Logbook</a></li>
        </ul>
    </nav>
    <main>
        <form class="form" action="home.php" method="POST"><input class="inputtext" type="text" placeholder="Your task!" name ="vsebina"><input class="inputsub" type="submit" value="ADD" name="add"></form>


        <?php
                class task extends RecursiveIteratorIterator {
                    function __construct($it) {
                        parent::__construct($it, self::LEAVES_ONLY);
                    }

                    function current() {
                        return "<input class=\"inputtext\" type=\"text\" Value=\"" . parent::current() . "\"  readonly> \n";
                    }           

                    function beginChildren() {
                        echo "<div class=\"form\">";
                    }
                    
                    function endChildren() {
                        echo "</div>";
                    }
                }
            $id = $_SESSION["id"];
            try {
                $stmt = $conn->prepare("SELECT vsebina FROM opravila WHERE uporabnikID = '$id'");
                $stmt->execute();
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach(new task(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    echo $v;
                }
              } catch(PDOException $e) {
                $msg = 'Database connection failed: ' . $e->getMessage();
                $msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg);
                debug_to_console($msg);
              }
        ?>

    </main>

    <?php
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
    
    ?>

    <?php
    $conn = null;
    ?>

</body>
</html>