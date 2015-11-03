<?php
    // ----------------------------------------------------------------------------
    // This file reads the input line of bitnodes_reg.conf and 
    // populates the text boxes on the on the bitnodes registration page 
    // .
    // ----------------------------------------------------------------------------
    $fh_3 = fopen ("/home/linaro/bitnodes_reg.conf", "r") or die ("Unable to open the bitnodes_reg.conf file");

	// NOTE: Validation needs to be incorporated here. 
    // In the event that the value is empty, we need to 
    // populate defaults 	
	if ($fh_3) {
        while (($line = fgets($fh_3)) !== false) {
            $param = explode("=", $line);				 
			switch ($param[0]) {
                case "extwebport":
				    if (empty ($param[1])) {
					    $extwebport = $extwebport_default;
					} else {
				        $extwebport=$param[1];
					}
				    break;

				// You aready have this value from bitcoin.conf.  I am only
				// allowing one bitcoin address for now.
                // case "address":
				//    $address=$param[1];
				//	break;
			}
		}
		fclose($fh_3);
	}
?>

