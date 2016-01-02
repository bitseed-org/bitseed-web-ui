<?php
echo "HELLO";
$HOME = "/home/linaro";
$max_peers=$_POST["max_peers"];
$minrelaytxfee=$_POST["minrelaytxfee"];
$limitfreerelay=$_POST["limitfreerelay"];
echo "$max_peers";
echo "$minrelaytxfee";
echo "$limitfreerelay";

// Create an initial array of default parameters
$params_default = array(
                "max_peers" => 125,
                "minrelaytxfee" => .00001,
                "limitfreerelay" => 15
                );

// If there is a value in the form element then assign 
// the parameter to th
$params_new = array (
    'max_peers' => $max_peers,
        'minrelaytxfee' => $minrelaytxfee,
        'limitfreerelay' => $limitfreerelay
        );

// ----------------------------------------------------------------
// Read in the bitcoin.conf file into the lines a normal array.
// Remove left and right whitespace as well as blank lines.
// ----------------------------------------------------------------
// $fh = fopen ("bitcoin.conf", "r") or die ("Unable to open file!");
// $fh = fopen ("$HOME/bitcoin.conf", "r") or die ("Unable to open file!");

// ------------------------------------------------------
//  For the parameters passed by the client,  take into 
//  account that the client may pass no value for any of
//  of the passed-in parameters.  In this case, assign 
//  them to default values.
// ------------------------------------------------------
$valid_lines = array();
foreach ($params_new as $key => $val) {
    if ($params_new[$key] !== "") {
        $valid_lines[$key] = $val;
    } else {
        $valid_lines[$key] = $params_default[$key];
    }
}
// print $valid_lines;
// Create a php array and then convert to a json object
// before writing to the mailbox.  minrelaytxfee, which 
// requires a floating point, 8 significant digit format,
// needs to be handled special.
foreach ($params_new as $key => $val) {
    if ($key == 'minrelaytxfee') {
        $valid_lines[$key] = number_format ($val, 8);
    }	
}
$json_object = json_encode($valid_lines);

// Write the json object to the mailbox.
$fh = fopen ("$HOME/wr_bconf_mbox", "w");
fwrite ($fh, $json_object); 
fclose ($fh);

// ----------------------------------------------------------------
// Next, write to the wr_bconf_flag.  In order to prevent multiple
// write transactions // not be lost, make sure that a '0' is read 
// before a '1' is written
// NOTE:  If a '1' is written while a '1' is still in queue, then the
// second write request will be missed.  Instead, if a user attempts
// to submit again before the last write operation is complete, then 
// print a status message to let them know that they need to wait 
// until the current operation is complete before submitting again.
// ----------------------------------------------------------------
$fh = fopen ("/home/linaro/wr_bconf_flag", "w+");
   if ($fh) {
                shell_exec ('echo "1" > /home/linaro/wr_bconf_flag');
   }
fclose ($fh);
?>
