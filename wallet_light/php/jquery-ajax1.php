<?php
// echo '<p>Hi I am some random ' . rand() .' output from the server.</p>';
// echo '<strong>Bitcoin is Enabled</strong>';
// echo "Data is";
$domvalue = $_GET['id'];

// Bitcoin controls
switch ($domvalue) {
    case "bitcoin_restart":
		 shell_exec ('echo "1" > /home/linaro/restartflag');
	     echo "Bitcoin is restarting";		
	     break;
    case "bitcoin_shutdown":
		 shell_exec ('echo "2" > btflags.txt');
	     echo "Bitcoin is not running";
	     break;

// Device Controls
    case "device_restart":
	     echo "Device is restarting";
		 shell_exec ('echo "1" > devflags.txt');
	     break;

    case "device_shutdown":
	     echo "Device is being shut down";
		 shell_exec ('echo "2" > /home/linaro/restartflag');
}

?>
