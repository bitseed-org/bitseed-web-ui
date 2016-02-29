<?php

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

	// ----------------------------------------------------
	// SLIDERS
	// ----------------------------------------------------
    $minrelaytxfee_conf=$array_from_json['minrelaytxfee']; 
	if ($minrelaytxfee_conf) {
        $minrelaytxfee = $minrelaytxfee_conf;
	}  

    $maxuploadtarget_conf=$array_from_json['maxuploadtarget']; 
	if ($maxuploadtarget_conf) {
        $maxuploadtarget = $maxuploadtarget_conf;
	}  

    $maxmempool=$array_from_json['maxmempool']; 
	if ($maxmempool_conf) {
        $maxmempool = $maxmempool_conf;
	}
   
   // ----------------------------------------------------
   // Checkboxes
   // ----------------------------------------------------
   $disablewallet_checked = "";
   $disablewallet_conf=$array_from_json['disablewallet']; 
   if ($disablewallet_conf == 1) {
       $disablewallet_checked = "checked";
   }

   $updateflag_checked = "";
   $updateflag_conf=$array_from_json['autoupdate']; 
   if ($updateflag_conf == 1) {
       $updateflag_checked = "checked";
   }
   $listenonion_checked = "";
   $listenonion_conf=$array_from_json['listenonion']; 
   if ($listenonion_conf == 1) {
       $listenonion_checked = "checked";
   }
   $onlynet_checked = "";
   $onlynet_conf=$array_from_json['onlynet']; 
   if ($onlynet_conf == 'onion') {
       $onlynet_checked = "checked";
   }
   $upnp_checked = "";
   $upnp_conf=$array_from_json['upnp']; 
   if ($upnp_conf == 1) {
       $upnp_checked = "checked";
   }
   $backupflag_checked = "";
   $backupflag_conf=$array_from_json['disablebackups']; 
   if ($backupflag_conf == 1) {
       $backupflag_checked = "checked";
   }
   // Write a '0' to rd_bconf_flag
   rewind ($fh_flag);
   $line = 0;
   fputs($fh_flag, $line);
   fclose($fh_flag);

?>
