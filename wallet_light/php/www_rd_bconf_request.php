<?php
   $max_peers = 125;
   $minrelaytxfee = .00001;
   $limitfreerelay = 10;

   // ------------------------------------------------------------------
   // This function issues a load request for loading bitcoin.conf
   // values. It will only issue a request if a rd_bconf_flag
   // has a '0'.  This is to prevent a new load request from 
   // initiating a new read operation when the previous operation
   // is still incomplete.
   // write a '1' to rd_bconf_flag to kick things off.
   // ------------------------------------------------------------------
   $fh_flag = fopen ("/home/linaro/rd_bconf_flag", "r+") or die ("Unable to open /home/linaro/rd_bconf_flag");
   if ($fh_flag) {
       if (($line = fgets($fh_flag)) !== false) {
			   if ($line == 0) {
                   $line = '1'; 
				   rewind ($fh_flag);
                   fputs($fh_flag, $line);
               }
       }
    }

    // ------------------------------------------------------------------
    // Now wait for the linaro side to fill the read mailbox and write a '2' to rd_bconf_flag
    // Once that occurs, you can unload the read mailbox, fill the text boxes 
    // in the control page, then write a '0' to rd_bconf_flag
    // ------------------------------------------------------------------
    while (1) {
        sleep(1);

        // Open file for read and writing - To detect 2 for your own action
        // and the ability to write '0' when your opertion is complete.
	    rewind($fh_flag);
        $line = fgets($fh_flag);
		$line = trim($line);

	    if ($line == '2') {
            break;
		}
    }
    // ------------------------------------------------------------------
    // A '2' has been detected.  Read the read mailbox and place a '0' in 
    // rd_bconf_flag.
    // ------------------------------------------------------------------
    rewind($fh_flag);
    $jsondata = file_get_contents('/home/linaro/rd_bconf_mbox');
    $array_from_json = json_decode($jsondata, true);

    // ------------------------------------------------------------------
    // Place the values from the array into the textboxes in the control page.
    // ------------------------------------------------------------------
    $max_peers_conf=$array_from_json['max_peers']; 
	if ($max_peers_conf) {
        $max_peers = $max_peers_conf;
	}
    $minrelaytxfee_conf=$array_from_json['minrelaytxfee']; 
	if ($minrelaytxfee_conf) {
        $minrelaytxfee = $minrelaytxfee_conf;
	}  
    $limitfreerelay_conf=$array_from_json['limitfreerelay']; 
	if (($limitfreerelay_conf)) {
        $limitfreerelay = $limitfreerelay_conf;
	}

   // Next, populate the checkboxes with the values from bitcoin.conf.
   $disablewallet_checked = "";
   $disablewallet_conf=$array_from_json['disablewallet']; 
   if ($disablewallet_conf == 1) {
       $disablewallet_checked = "checked";
   }

   $txindex_checked = "";
   $txindex_conf=$array_from_json['txindex']; 
   if ($txindex_conf == 1) {
       $txindex_checked = "checked";
   }

   $konn_checked = "";
   $konn_conf=$array_from_json['konn']; 
   if ($konn_conf == 1) {
       $konn_checked = "checked";
   }

   // Write a '0' to rd_bconf_flag
   rewind ($fh_flag);
   $line = 0;
   fputs($fh_flag, $line);
   fclose($fh_flag);

?>
