<div data-role="content" class="content">

	    <div style="margin-top:15px;margin-left:5px;">
		  <span id="span_WalletHeaderText">Bitcoin & Device Control</span>
	   	  <hr class="hr_wallet">
	    </div>
	   
        <div class="ui-content">
		 <span class="status_title">Status</span><br />
		 <span class="status_text" id="device_status"></span>
            <hr class="hr_secondary_wallet">
            <?php 
			    // --------------------------------------------------------------------------------------
                // Open Updateflag to see if there is a '1'.  If there is, then write a message to the
                // user that new software is available.    
			    // We should figure out a more graceful way of recoveriing from a failed open.
			    // --------------------------------------------------------------------------------------
                $fh=fopen("/home/linaro/updateflag", "r") or die ("Unable to open updateflag file");
                $line = fgets($fh);
                if (preg_match("/^1/", $line)) {
                    print "<h3>Software updates are available</h3>";
                }
                fclose($fh);
            ?>
         
			<br />
            <div data-role="controlgroup" data-type="horizontal" style="text-align: left;">
                <input type="button" value="Update software" id="update-software" onClick="bitcoinControl(this);" />
	        </div>
            <!-- <hr class="hr_secondary_wallet"> -->
			<br />
            <div data-role="controlgroup" data-type="horizontal" style="text-align: left;">
                <input type="button" value=" Restart Bitcoin " id="bitcoin_restart" onClick="bitcoinControl(this);" />
		    </div>
			<br />
            <div data-role="controlgroup" data-type="horizontal" style="text-align: left;">
			    <input type="button" value="Shutdown Bitseed" id="device_shutdown" onClick="bitcoinControl(this);" />
		    </div>
	    </div>
  </div> 
