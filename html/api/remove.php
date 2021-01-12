<?php
$servername = "localhost";
$username = "webuser";
$password = "rtspadmin";
$dbname = "webgui";

if (isset($_GET["stream"])) {
    $stream = $_GET["stream"];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    // sql to delete a record
    $sql = "DELETE FROM streams WHERE streamPath='$stream'";

    if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
    header("Location: ../index.php");
    die();
    } else {
    echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}

?>