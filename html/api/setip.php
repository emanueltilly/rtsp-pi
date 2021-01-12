<?php
session_start();
// Get text file contents as array of lines
$filepath = '/etc/dhcpcd.conf';
$txt = file($filepath); 
$line = 60;

//check post
if (isset($_GET["ip"]) && isset($_GET["mask"])) {
    $ip = $_GET["ip"];
    $mask = $_GET["mask"];
    
    //DATABASE UPDATE
    $servername = "localhost";
    $username = "webuser";
    $password = "rtspadmin";
    $dbname = "webgui";

    //IP
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE variables SET variableValue='$ip' WHERE variableName='serverIP'";

    if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
    } else {
    echo "Error updating record: " . $conn->error;
    }

    $conn->close();

    //MASK
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE variables SET variableValue='$mask' WHERE variableName='serverMask'";

    if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
    } else {
    echo "Error updating record: " . $conn->error;
    }

    $conn->close();

    //SYSTEM UPDATE
    $update = "static ip_address=" . $ip . "/" . $mask . "\n";
    echo $update;
    // Make the change to line in array
    $txt[$line] = $update; 
    // Put the lines back together, and write back into txt file
    file_put_contents($filepath, implode("", $txt));
    //success code
    sleep(2);
    $cmd = "sleep 5; sudo /sbin/shutdown -r now";
    $output = shell_exec($cmd);
    header('Location: http://' . $ip);
    //echo "<pre>$output</pre>";
    die();
        
} else {
    echo 'Error';
    die();
}
?>