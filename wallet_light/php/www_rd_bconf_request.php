<?php
   $max_peers = 125;
   $minrelaytxfee = .00001;
   $limitfreerelay = 10;
   // This function issues a load request for loading bitcoin.conf
   // values. It will only issue a request if a rd_bconf_flag
   // has a '0'.  This is to prevent a new load request from 
   // initiating a new read operation when the previous operation
   // is still incomplete.
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

    // Now wait for the linaro side to fill the read mailbox and write a '2' to rd_bconf_flag
    // Once that occurs, you can unload the read mailbox, fill the text boxes 
    // in the control page, then write a '0' to rd_bconf_flag

    while (1) {
         
        sleep(1);

        // Open file for read and writing - To detect 2 for your own action
        // and the ability to write '0' when your opertion is complete.
	    // There is only one line in the flag files.
	    rewind($fh_flag);
        $line = fgets($fh_flag);
		$line = trim($line);

	    if ($line == '2') {
            break;
		}
    }
		    // echo "Made it here";
    // A '2' has been detected.  Read the read mailbox and place a '0' in 
    // rd_bconf_flag.
    rewind($fh_flag);
	$kh = fopen ("/home/linaro/temp_log", "w");
    $jsondata = file_get_contents('/home/linaro/rd_bconf_mbox');
    $array_from_json = json_decode($jsondata, true);

    // Place the values from the array into the textboxexes in the control page.
    // $max_peers=$array_from_json['max_peers']; 
    $max_peers_conf=$array_from_json['max_peers']; 
	// echo "max_peers_conf $max_peers_conf";
	if ($max_peers_conf) {
        $max_peers = $max_peers_conf;
	}
	fputs($kh, $max_peers);
	fputs($kh, $max_peers_conf);
    $minrelaytxfee_conf=$array_from_json['minrelaytxfee']; 
	// echo "minrelaytxfee_conf  $minrelaytxfee_conf";
	if ($minrelaytxfee_conf) {
        $minrelaytxfee = $minrelaytxfee_conf;
	}  
	fputs($kh, $minrelaytxfee);
    $limitfreerelay_conf=$array_from_json['limitfreerelay']; 
	// echo "limitfreerelay_conf $limitfreerelay_conf";
	if (($limitfreerelay_conf)) {
        $limitfreerelay = $limitfreerelay_conf;
	}
	fputs($kh, $limitfreerelay);

   // Write a '0' to rd_bconf_flag
   rewind ($fh_flag);
   $line = 0;
   fputs($fh_flag, $line);
   fclose($fh_flag);

?>
