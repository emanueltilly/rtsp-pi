<script>
function catchReload(){
  window.alert("The Pi will now reboot...\nPress OK to continue...");
  setTimeout(location.reload.bind(location), 500);
}
</script>


<?php

$servername = "localhost";
$username = "webuser";
$password = "rtspadmin";
$dbname = "webgui";

$streamLive = "N/A";
$serverIP = "N/A";
$serverMask = "N/A";


//GET STREAM CURRENTLY LIVE
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT streamPath FROM streams WHERE live = 1 LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $streamLive = $row["streamPath"];
  }
}
$conn->close();


//GET CURRENT IP
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT variableValue FROM variables WHERE variableName = 'serverIP' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $serverIP = $row["variableValue"];
    
  }
}
$conn->close();


//GET CURRENT MASK
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT variableValue FROM variables WHERE variableName = 'serverMask' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $serverMask = $row["variableValue"];
    
  }
}
$conn->close();


?>



<html>
<head>
<title>RTSP Pi</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="img/icon.png" />
</head>
<body>

    <div class="mainBox">
        <img src="img/icon.png"><br>
        <h1>RTSP Pi</h1>
        <h3>Streaming:<br><?php echo $streamLive ?></h3><br><br>

        <button onclick="restart();"class="formsubmit">
            Reboot
        </button>

        <button onclick="shutdown();"class="formsubmit">
            Shutdown
        </button>

        <br><br><br>




        <?php
        //KNOWN STREAMS

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM streams";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

        echo "<h3>Stream history:</h3>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $buttonFlag = 0;
            if ($row["live"] == 1) {
                $buttonFlag = 1;
            } else {
                $buttonFlag = 0;
            }

            echo "<br> <button onclick=\"window.location.href='api/replaceline.php?stream=" . $row["streamPath"] . "'; catchReload()\" class=\"historyButtons" . $buttonFlag . "\">";
            echo $row["streamPath"];
            echo "</button>";

            
            echo "<button onclick=\"fetch('api/remove.php?stream=" . $row["streamPath"] . "'); location.reload()\" class=\"historyRemoveButton\">Forget</button>";
            
        
        
        }
        echo "<br><br><br>";
        }
        $conn->close();
        ?>






        
        <form action="api/replaceline.php" method="get" onSubmit="catchReload()">
            <label for="stream">Add RTSP stream:</label><br>
            <input type="text" id="stream" name="stream" class="formfield" placeholder="<?php echo $streamLive ?>" required><br><br>
            <input type="submit" value="Apply and Reboot" class="formsubmit">
        </form>
        
        <form action="api/setip.php" method="get" onSubmit="catchReload()">
            <label for="ip">New IP address:</label><br>
            <input type="text" id="ip" name="ip" class="formfield" placeholder="<?php echo $serverIP ?>" required><br><br>
            <label for="mask">Subnet mask (CIDR form):</label><br>
            <input type="text" id="mask" name="mask" class="formfield" placeholder="<?php echo $serverMask ?>" required><br><br>
            <input type="submit" value="Apply and Reboot" class="formsubmit">
        </form>
    </div>

    <p class="footer">
        RTSP Pi Release 1.0 Beta 1<br><br>
        This software is provided under the Apache License 2.0 license.<br>
        This software is to be USED AT YOUR OWN RISK and is provided WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND.<br>
        This software and the device running the software should UNDER NO CIRCUMSTANCES BE CONNECTED TO THE INTERNET.<br>
        RTSP Pi is based on the <a href="https://github.com/Anonymousdog/displaycameras">Displaycameras-project by "Anonymousdog"</a><br>
        Download the latest version of RTSP Pi on <a href ="https://github.com/emanueltilly/rtsp-pi">https://github.com/emanueltilly/rtsp-pi</a><br>
        Icon made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
    </p>

</body>
</html>


<script>



function restart(){
  var r = confirm("Are you sure you want to reboot the Pi?");
  if (r == true) {
    fetch('api/restart.php');
    window.alert("The Pi is rebooting. The page will refresh automatically.");
    setTimeout(location.reload.bind(location), 15000);
  } 
}

function shutdown(){
  var r = confirm("Are you sure you want to shutdown the Pi?\nYou will not be able to turn it back on remotley again.");
  if (r == true) {
    fetch('api/shutdown.php');
    window.alert("The Pi is shutting down...");
    setTimeout(location.reload.bind(location), 5000);
  } 
}



</script>