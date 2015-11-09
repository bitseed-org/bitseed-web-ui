<?php

$HOME = "/home/linaro";

// Values submitted from client-side form
$extwebport=$_POST['extwebport'];
$address=$_POST['btc_addr'];

// Create an initial array of default parameters
$params_default = array(
				"extwebport" => 80,
				"address" => $address
				);

$params_new = array(
    'extwebport' => $extwebport,
	'address' => $address
	);
$file_logger = fopen ("$HOME/bitnodes_reg.log", "w");
$extip = file_get_contents('http://ipecho.net/plain');
$urlnodes = $extip . "-8333";
// ----------------------------------------------------------------
// Read in the bitcoin.conf file into the lines a normal array.
// Remove left and right whitespace as well as blank lines.
// ----------------------------------------------------------------
//  If there is no bitnodes_reg.conf file, then choose one with default 
//  values.  The only one that should be necessary will be the variable 
//  $extwebport.   
$fh = fopen ("$HOME/bitnodes_reg.conf", "r") or die ("Unable to open file!");
$lines = array();
while (!feof($fh)) {
		$temp_string = trim(fgets($fh));
		if ($temp_string != "") {
            $lines[] = $temp_string; 
	    }
}
fclose($fh);

// for ($line_cnt = 0; line_cnt < count($lines); $line_cnt++) {
//     fwrite ($file_logger, $lines[$line_cnt]); 
// }
// fclose($file_logger);

// ------------------------------------------------------
// Split into commented_lines[] and $valid_lines[] 
// respectively.
// ------------------------------------------------------
$commented_lines = array();
$valid_lines = array();
for ($i=0; $i<count($lines);$i++) {
    // $line = trim($lines[$i]); 
    $line = $lines[$i]; 
    if (preg_match("/^#/", $line)) {
        $commented_lines[] = $line; 
	}
	else { 
       list($key, $val) = explode("=", $line);
	   $valid_lines[$key] = $val;
    }
}

// ------------------------------------------------------
//  For the parameters passed by the client,  take into 
//  account that the client may pass no value for any of
//  of the passed-in parameters.  In this case, assign 
//  them to default values.
// ------------------------------------------------------
foreach ($params_new as $key => $val) {
	if ($params_new[$key] !== "") {
 	    $valid_lines[$key] = $val;
	} else {
	    $valid_lines[$key] = $params_default[$key];
    }
}
// ------------------------------------------------------
// Create output lines from the value_lines associatve 
// array.
// ------------------------------------------------------
foreach ($valid_lines as $key => $val) {
    echo "$key"."="."$val"."\n";
}
// ------------------------------------------------------
//  Commented lines are preserved and print to the output 
//  buffer directly at the end.
// ------------------------------------------------------
for ($i=0; $i<count($commented_lines); $i++) {
		echo "$commented_lines[$i]"."\n";
}

// ------------------------------------------------------
// Before creating the new bitnodes_reg.conf file, copy the 
// original to bitnodes_reg.conf.bak.  Delay display of copy
// message to #bitcoin_status DOM element until after
// the output buffer is flushed.
// ------------------------------------------------------
if (!copy("$HOME/bitnodes_reg.conf", "$HOME/bitnodes_reg.conf.bak")) {
    $cp_msg = "Failed to create bitnodes.conf.bak backup file!";
} else {
    $cp_msg = "$HOME/bitnodes_reg.conf has been updated.  Original file is in $HOME/bitnodes.conf.bak";
}
		
// Returns content of the output buffer
$finalStr = ob_get_contents();
ob_end_clean();
file_put_contents("$HOME/bitnodes_reg.conf", $finalStr);

echo "$cp_msg";

// -----------------------------------------------------
//  FINALLY, perform the actual bitnodes registration.
// -----------------------------------------------------
// These are Bash shell commands that 
// $command = curl -H \"Accept: application/json; indent=4\" -d \"bitcoin_address=$address\" -d \"url=http:\/\/$ipadr:$prt" https:\/\/getaddr.bitnodes.io\/api\/v1\/nodes\/$urlnodes\/
// $command = 'curl -H ' . '"Accept: application/json; indent=4"' . '-d' . '"bitcoin_address=$address\"' . '"-d"' . '"url=http://' . "$extip:$extwebport" . '"https:\\getaddr.bitnodes.io/api/v1/nodes/$urlnodes/'
// --->  This is the correct one to use - KD 08222015 $command = 'curl -H ' . '"Accept: application/json; indent=4" ' . '-d ' . '"bitcoin_address= ' . "$address\" " . '-d ' . '"url=http://' . "$extip:$extwebport\" " . 'https://getaddr.bitnodes.io/api/v1/nodes/' . "$urlnodes";

fwrite($file_logger, "$command\n");
flose ($file_logger);

return;
?>
