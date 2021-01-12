<?php
    $cmd = "sudo /sbin/shutdown -r now";
    $output = shell_exec($cmd);
    echo "<pre>$output</pre>";
    die();
?>