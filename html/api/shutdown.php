<?php
$cmd = "sudo /sbin/shutdown now";
$output = shell_exec($cmd);
echo "<pre>$output</pre>";
die();
?>