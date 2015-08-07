<?php
// echo '<p>Hi I am some random ' . rand() .' output from the server.</p>';
// echo '<strong>Bitcoin is Enabled</strong>';
// echo "Data is";
$domvalue = $_GET['id'];

// Bitcoin controls
switch ($domvalue) {
    case "bitcoin_restart":
	     echo "Bitcoin has been restarted";
		 shell_exec ('echo "1" > btflags.txt');
	     break;
    case "update-software":
	     echo "System Software is being updated";
		 shell_exec ('echo "2" > updateflag');
	     break;

// Device Controls
    case "device_shutdown":
	     echo "Device is being shut down";
		 shell_exec ('echo "1" > devflags.txt');
		 break;
}

// Open the updateflag file.  If it is written with a '1', then 
// alert the user that a new software update is available.
// $fh = fopen("home/linaro/updateflag", "r");
// Read out the first line to see if it contains '1' as the sole entry
//if ($fh) {
//    while (($line = fgets($fh)) !== false) {
//        // process.  In this case, there is only a single entry of '1'.
//        // if preg_match(("/^1/$"), $line) {
//        if preg_match(("/^1/$"), $line) {
//	        echo "Software updates are available");
//		}
//	}
//} else {
//    echo "There was an error in opening the updateflag file"; 
//}
//fclose($fh); 


?>
