<?php
// echo '<p>Hi I am some random ' . rand() .' output from the server.</p>';
// echo '<strong>Bitcoin is Enabled</strong>';
// echo "Data is";
$domvalue = $_GET['id'];

switch ($domvalue) {
    case "bitcoin_enable":
	     $out = shell_exec("/home/linaro/btcwatch.sh 2>&1");
		 echo $out;
		 if (!empty($out)) {
	        echo "Bitcoin is enabled";		
		 } else {
		     echo "There was an error in trying to start Bitcoin";
	     }
	     break;
    case "bitcoin_disable":
         echo shell_exec ('ls 2>&1'); 
	     // echo "Bitcoin is disabled";
	     break;
    case "bitcoin_restart":
		 echo shell_exec ('~/btcstop.sh');
		 echo shell_exec ('~/btcwatch.sh');
	     // echo "Bitcoin has been restarted";		
	     break;
}

?>
