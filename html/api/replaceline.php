<?php
session_start();
// Get text file contents as array of lines
$filepath = '/home/pi/displaycameras/layout.conf.default';
$txt = file($filepath); 
$line = 38;

//check post
if (isset($_GET["stream"])) {
    
    $update = "\"" . $_GET['stream'] . "\" \\" . "\n";
    // Make the change to line in array
    $txt[$line] = $update; 
    // Put the lines back together, and write back into txt file
    file_put_contents($filepath, implode("", $txt));
    //success code
    

    //UPDATE DATABASE
    $servername = "localhost";
    $username = "webuser";
    $password = "rtspadmin";
    $dbname = "webgui";

    $stream = $_GET["stream"];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    //ZERO OUT LIVE FLAGS
    $sql = "UPDATE streams SET live=0";

    if ($conn->query($sql) === TRUE) {
    echo "Live flags are zero<br>";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }

    //CHECK IF VALUE IS ALREADY IN DATABASE
    $sql = "SELECT * FROM streams WHERE streamPath='$stream' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo "STREAM IS IN DATABASE<BR>Setting live flag...";


            $sql = "UPDATE streams SET live=1 WHERE streamPath='$stream'";

            if ($conn->query($sql) === TRUE) {
            echo "Live flag is set to 1<br>";
            } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


      }
    } else {
        echo "STREAM IS NOT IN DATABASE<BR>";


        //ADD NEW VALUE
        $sql = "INSERT INTO streams (streamPath, live)
        VALUES ('$stream', '1')";

        if ($conn->query($sql) === TRUE) {
        echo "New database record made sucessfully <br>";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
    $conn->close();

    echo 'Success with layout update. Rebooting...';
    $cmd = "sudo /sbin/shutdown -r now";
    $output = shell_exec($cmd);
    
    //echo "<pre>$output</pre>";
    die();
        
} else {
    echo 'Error! No stream was given to the API';
    die();
}
?>