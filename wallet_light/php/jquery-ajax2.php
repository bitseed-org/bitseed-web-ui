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
		 shell_exec ('echo "1" > update_software.txt');
	     break;

// Device Controls
    case "device_shutdown":
	     echo "Device is being shut down";
		 shell_exec ('echo "1" > devflags.txt');
		 break;
}

?>
