<?php
$HOME = "/home/linaro";

$full_chkbox_array = ['disablewallet', 'txindex', 'konn'];

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// EXTRACT VALUES FROM UI BCUPDATE FORM SUBMIT
// ----------------------------------------------------------
// Process numeric boxes - 
// ----------------------------------------------------------
// Note that checkboxes are processed with an array type.  
// This method should be compatible with All input types.  
// Investigate unified methods for all input 
// forms here.
// ----------------------------------------------------------
$max_peers=$_POST["max_peers"];
$minrelaytxfee=$_POST["minrelaytxfee"];
$limitfreerelay=$_POST["limitfreerelay"];

// ----------------------------------------------------------
// Process checkboxes
// ----------------------------------------------------------
//  Checkbox array
$bitcoin_conf_chkbox = count($_POST['bitcoin_conf_chkbox']) ? $_POST['bitcoin_conf_chkbox'] : array();
$slider_val = $_POST['slider-1'];
// echo $bitcoin_conf_chkbox;
// echo count($bitcoin_conf_chkbox) ? implode(', ', $bitcoin_conf_chkbox) : 'Nothing ';
// $bitcoin_conf_list = array_values($bitcoin_conf_chkbox);
// ----------------------------------------------------------
// The checkbox arrays come put in the form of indexed arrays.
// To remain consistent with the way numeric boxes are handled,
// swap the value and the key.
// ----------------------------------------------------------
// echo var_dump($bitcoin_conf_chkbox);
$chkbox_values = array();
for ($i = 0; $i < count($full_chkbox_array); $i++) { 
	$cbox_item = $full_chkbox_array[$i];
	if (in_array($cbox_item, $bitcoin_conf_chkbox)) {
        $chkbox_values[$cbox_item] = "1";
    } else {
	    $chkbox_values[$cbox_item] = "0";
    }
}
// echo var_dump($chkbox_values);

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// CREATE AN INITIAL ARRAY OF DEFAULT PARAMETERS
// ----------------------------------------------------------
$params_default = array( 
                'max_peers' => 125,
                'minrelaytxfee' => 0.000010000,
                'limitfreerelay' => 15,
                'disablewallet' => 1,
				'txindex' => 1,
			    'konn' => 0,
                'slider-1' => 45
				);

// ----------------------------------------------------------


// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// If there is a value in the form element then assign 
// the parameter to th
// ----------------------------------------------------------
// Numeric Boxes
$params_new = [
        'max_peers' => $max_peers,
        'minrelaytxfee' => $minrelaytxfee,
        'limitfreerelay' => $limitfreerelay
        ];
// Add checkboxes to $params_new
$params_new = array_merge($params_new, $chkbox_values);

// Add sliders to $params_new
$slider_array = array(
				'slider-1' => $slider_val 
				);
$params_new = array_merge($params_new, $slider_array);

// echo var_dump($params_new);

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
// echo var_dump($valid_lines);
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
// echo var_dump($json_object);

// Write the json object to the mailbox.
$fh = fopen ("$HOME/wr_bconf_mbox", "w");
fwrite ($fh, $json_object); 
fclose ($fh);

$fh = fopen ("/home/linaro/wr_bconf_flag", "w+");
   if ($fh) {
                shell_exec ('echo "1" > /home/linaro/wr_bconf_flag');
   }
fclose ($fh);

echo "Bitcoin configuration parameters have been updated.  Restarting bitcoind...Please wait 15 minutes to resume normal operation. ";
shell_exec ('echo "1" > /home/linaro/restartflag'); // Comment this line out for testing purposes
?>
