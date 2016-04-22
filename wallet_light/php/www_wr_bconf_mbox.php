<?php
$HOME = "/home/linaro";

$full_chkbox_array = ['autoupdate', 'listenonion', 
		              'onlynet', 'upnp', 'enablebackups'];

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
// $max_peers=$_POST["max_peers"];
// $limitfreerelay=$_POST["limitfreerelay"];

// ----------------------------------------------------------
// Process checkboxes
// ----------------------------------------------------------
//  Checkbox array
$bitcoin_conf_chkbox = count($_POST['bitcoin_conf_chkbox']) ? $_POST['bitcoin_conf_chkbox'] : array();
//  Grab current slider values
$minrelaytxfee=$_POST['minrelaytxfee'];
$maxuploadtarget=$_POST['maxuploadtarget'];
$maxmempool=$_POST['maxmempool'];
// echo " - $minrelaytxfee ";
// echo " - $maxuploadtarget ";
// echo " - $maxmempool ** ";
// $slider_val = $_POST['slider-1'];
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
//  Defaults reall only apply to writes to the bitcoin 
//  configuration file when a user has not specified a 
//  value in the ui.  In this case, the default value 
//  would be written to the config file.  HOWEVER, this is 
//  really only useful for numeric or text boxes.  We are 
//  only using checkboces and sliders.  initial (default) values 
//  should be specified in the configuration file.  
//  TAG THIS BLOCK FOR LATER REMOVAL
// ----------------------------------------------------------
$params_default = array( 
                'minrelaytxfee' => 0.00001000,
				'maxuploadtarget' => 100000,
				'maxmempool' => 300,
				'autoupdate' => 1,
                'listenonion' => 1,
				// 'onlynet' => "onion",
				'upnp' => 1,
				'enablebackups' => 0
				);

// ----------------------------------------------------------


// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// If there is a value in the form element then assign 
// the parameter to th
// ----------------------------------------------------------
// Numeric Boxes
// $params_new = [
//         'max_peers' => $max_peers,
//         'minrelaytxfee' => $minrelaytxfee,
//         'limitfreerelay' => $limitfreerelay
//         ];
// Add checkboxes to $params_new
// $params_new = array_merge($params_new, $chkbox_values);

// Add sliders to $params_new
$slider_array = array(
				'minrelaytxfee' => $minrelaytxfee,
				'maxuploadtarget' => $maxuploadtarget,
				'maxmempool' => $maxmempool
				);

$params_new = array_merge($chkbox_values, $slider_array);

// echo var_dump($slider_array);
// echo var_dump($params_new);

// ------------------------------------------------------
//  For the parameters passed by the client,  take into 
//  account that the client may pass no value for any of
//  of the passed-in parameters.  In this case, assign 
//  them to default values.
//
//  This is code that is left over from when textboxes
//  were used.  With sliders and checkboxes, there are
//  no "" (empty) inputs - The else clause may be 
//  taken out.
// ------------------------------------------------------
//  params_new contains all of the values from the
//  sliders and checkboxes.
// ------------------------------------------------------
// foreach ($params_new as $key => $val) {
//    if ($params_new[$key] !== "") {
//         $valid_lines[$key] = $val;
//    } else {
//         $valid_lines[$key] = $params_default[$key];
//    }
// }
// echo var_dump($valid_lines);
// Create a php array and then convert to a json object
// before writing to the mailbox.  minrelaytxfee, which 
// requires a floating point, 8 significant digit format,
// needs to be handled special.
$valid_lines = array();
foreach ($params_new as $key => $val) {
    $valid_lines[$key] = $val;
    if ($key == 'minrelaytxfee') {
        // $valid_lines[$key] = number_format ($val, 8);
		// Convery from Satoshi to BTC 
        // BTC = m * 1e-8; m = number of satoshis; format:float
        $temp_val = floatval($val) * .00000001;
        $valid_lines[$key] = number_format($temp_val, 8); 
// echo var_dump($valid_lines);
    }	
	if ($key == 'onlynet') {
        if ($valid_lines[$key] == 1) {
            $valid_lines[$key] = 'onion';
	    } else {
            $valid_lines[$key] = "";
        }
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
// shell_exec ('echo "1" > /home/linaro/restartflag'); // Comment this line out for testing purposes
?>
