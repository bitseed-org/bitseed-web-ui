    <!-- Special note on Tor Enable and onlynet

         3 modes:
	             1.  Tor=1, onlynet is blank line - Default - no line.  Tor and ipv4
			     2.  Tor Enable = 1 and onlynet=onion - Tor only
			     3.  Tor Enable = 0 - No Tor
    -->	 

	<div data-role="content" class="content">

	    <div style="margin-top:15px;margin-left:5px;">
		  <span id="span_WalletHeaderText">Bitcoin Configuration</span>
	   	  <hr class="hr_wallet">
	    </div>
	   
        <div class="ui-content">
		 <span class="status_title">Status</span><br />
		 <span class="status_text" id="bitcoin_status"></span>
            <hr class="hr_secondary_wallet">
             <!-- <form name="bitcoinconf_form" id="bitcoinconf_form" action="php/www_wr_bconf_mbox.php" method="post"> -->
             <form name="bitcoinconf_form" id="bitcoinconf_form" method="post">
		        <fieldset class="ui-field-contain">
					<div>
                        <?php // $disablewallet__tt_content = "A lengthier string"; ?>
					    <!-- Disable Wallet checkbox -->
					    <!-- <input type="checkbox" name="bitcoin_conf_chkbox[]" id="disableWallet_id" value="disablewallet" <?php // echo $disablewallet_checked ?> />
					    <label rel="tootip" title=<?php // echo $disablewallet_tt_content; ?> for="disableWallet_id">Disable Wallet</label> -->

					    <!-- Auto Updates checkbox -->
					    <input type="checkbox" name="bitcoin_conf_chkbox[]" id="autoupdate_id" value="autoupdate" <?php echo $updateflag_checked; ?>/>
					    <label rel="tootip" title=<?php echo $autoupdate_tt_content; ?> for="autoupdate_id">Auto Update</label>

					    <!-- Tor On/Off checkbox -->
					    <input type="checkbox" name="bitcoin_conf_chkbox[]" id="listenonion_id" value="listenonion" <?php echo $listenonion_checked; ?> />
					    <label rel="tootip" title=<?php echo $listenonion_tt_content; ?> for="listenonion_id">Tor Enable</label>
						
					    <!-- Tor Only checkbox -->
					    <input type="checkbox" name="bitcoin_conf_chkbox[]" id="onlynet_id" value="onlynet" <?php echo $onlynet_checked; ?> />
					    <label rel="tootip" title=<?php echo $onlynet_tt_content; ?> for="onlynet_id">Tor Only</label>
						
					    <!-- Universal Plug and Play checkbox -->
					    <input type="checkbox" name="bitcoin_conf_chkbox[]" id="upnp_id" value="upnp" <?php echo $upnp_checked; ?> />
					    <label rel="tootip" title=<?php echo $upnp_tt_content; ?> for="upnp_id">UPnP Enable</label>

					    <!-- Disable Blockchain Backups checkbox -->
					    <input type="checkbox" name="bitcoin_conf_chkbox[]" id="enablebackups_id" value="enablebackups" <?php echo $backupflag_checked; ?> />
					    <label rel="tootip" title=<?php echo $disablebackups_tt_content; ?> for="enablebackups_id">Blockchain Backup Enable</label>

				    </div>
					<!-- <div class="ui-input-text" style="width: 160px !important; border-width: 0px; display: inline-block;">
                        <label rel="tootip" title = "test2" for="max_peers" style="display: inline-block"><h4>Max Connections:</h4></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; display: inline-block;">
                        <input type="number" name="max_peers" id="max_peers" min="1" max="125" value=<?php echo $max_peers ?> placeholder="125"/>
				    </div>
					<br />
					<div class="ui-input-text" style="width: 160px !important; border-width: 0px; display: inline-block;"> 
                        <label for="minrelaytxfee"><h4>Min Relay Tx Fee:</h4></label>
					</div>
					<div class="ui-input-text" style="width: 130px !important; border-width: 0px; display: inline-block;">
                        <input type="number" step="any" name="minrelaytxfee" id="minrelaytxfee" min=".000001" max=".1" value=<?php // echo $minrelaytxfee ?> placeholder=".0000100000"/>
					</div>
					<br />
					<div class="ui-input-text" style="width: 160px !important; border-width: 0px; display: inline-block;">
                        <label for="limitfreerelay"><h4>Limit Free Relay:</h4></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; margin: 0px; display: inline-block;">
                        <input type="number" name="limitfreerelay" id="limitfreerelay" min="1" max="25" value=<?php // echo $limitfreerelay ?> placeholder="15"/>
					</div> -->
					<br />
                    <!--<div class="ui-field-contain" style="width: 400px !important; display: inline-block"> -->
                    <!-- <div class="ui-field-contain">
                            <label rel="tootip" title="slider help" for="slider-1" style="width: 140px !important; border-width: 0px; display: inline-block;"><strong>Input slider:</strong></label>
                            <input type="range" name="slider-1" id="slider-1" value=<?php // echo $slider_val ?> min="0" max="100" data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div> -->
                    <div class="ui-field-contain">
<!--                            <label rel="tootip" title=<?php echo $minrelaytxfee_tt_content; ?> for="minrelaytxfee_id" style="width: 200px !important; border-width: 0px; display: inline-block;"><strong>Min Relay Tx Fee (.000001-.01):</strong></label><br /> -->
                            <label rel="tootip" title=<?php echo $minrelaytxfee_tt_content; ?> for="minrelaytxfee_id" style="width: 200px !important; border-width: 0px; display: inline-block;"><strong>Min Relay Tx Fee (Satoshi) (1000 - 50000):</strong></label><br />
                            <input type="range" name="minrelaytxfee" id="minrelaytxfee_id" value=<?php echo $minrelaytxfee; ?> min="1000" max="50000" step="1" data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div>
                    <div class="ui-field-contain">
                            <label rel="tootip" title=<?php echo $maxuploadtarget_tt_content; ?> for="maxuploadtarget_id" style="width: 200px !important; border-width: 0px; display: inline-block;"><strong>Daily Upload Limit (MB) (144-5000):</strong></label><br />
                            <input type="range" name="maxuploadtarget" id="maxuploadtarget_id" value=<?php echo $maxuploadtarget; ?> min="144" max="5000" data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div>
                    <div class="ui-field-contain">
                            <label rel="tootip" title=<?php echo $maxmempool_tt_content; ?> for="maxmempool_id" style="width: 200px !important; border-width: 0px; display: inline-block;"><strong>Mempool Size Limit (100-500):</strong></label><br />
                            <input type="range" name="maxmempool" id="maxmempool_id" value=<?php echo $maxmempool; ?> min="100" max="500" data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div>
					<br /><br />

                    <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                        <input type="submit" name="submit" value="Update bitcoin.conf" id="update-bitcoin-config" />
	                </div>	
			   </fieldset>
		    </form>
            <!--- <hr class="hr_secondary_wallet"> -->
            <?php 
                // Open Updateflag to see if there is a '1'.  If there is, then write a message to the
                // user that new software is available.    
			    // We should figure out a more graceful way of recoveriing from a failed open.
                // --- $fh=fopen("/home/linaro/updateflag", "r") or die ("Unable to open updateflag file");
                // --- $line = fgets($fh);
                // --- if (preg_match("/^1/", $line)) {
                // ---     print "<h3>Software updates are available</h3>";
                // --- }
                // --- fclose($fh);
            ?>
            <!-- <h3>Software updates are available</h3> -->
         
            <!-- <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                <input type="button" value="Update software" id="update-software" onClick="bitcoinControl(this);" />
	        </div>
            <hr class="hr_secondary_wallet">
            <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                <input type="button" value=" Restart Bitcoin " id="bitcoin_restart" onClick="bitcoinControl(this);" />
		    </div> -->  
            <!--- <div data-role="controlgroup" data-type="horizontal" style="text-align: center;"> -->
			    <!-- <input type="button" value="Shutdown Bitseed" id="device_shutdown" onClick="deviceControl(this);" /> -->
			    <!--- <input type="button" value="Shutdown Bitseed" id="device_shutdown" onClick="bitcoinControl(this);" />
		    </div> -->
		    <!-- <hr class="hr_secondary_wallet"> -->
		    <!-- <hr class="hr_secondary_wallet"> -->
            <!-- </fieldset> -->
	    <!-- </form> -->
	</div>
</div> 

