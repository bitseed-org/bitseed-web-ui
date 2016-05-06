    <!-- Special note on Tor Enable and onlynet

         3 modes:
	             1.  Tor=1, onlynet is blank line - Default - no line.  Tor and ipv4
			     2.  Tor Enable = 1 and onlynet=onion - Tor only
			     3.  Tor Enable = 0 - No Tor
    -->	 

	<div data-role="content" class="content">

	    <div style="margin-top:15px;margin-left:5px;">
		  <span id="span_WalletHeaderText">Settings</span>
	   	  <hr class="hr_wallet">
	    </div>
	   
        <div class="ui-content">
		   <span class="status_text" id="bitcoin_status"></span> 
             <form name="bitcoinconf_form" id="bitcoinconf_form" method="post">
		        <fieldset class="ui-field-contain">
					<div>
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
					    <input type="checkbox" name="bitcoin_conf_chkbox[]" id="disablebackups_id" value="disablebackups" <?php echo $backupflag_checked; ?> />
					    <label rel="tootip" title=<?php echo $disablebackups_tt_content; ?> for="disablebackups_id">Blockchain Backup Enable</label>

				    </div>

					<!-- Min Relay Tx Fee Slider Control -->
                    <div class="ui-field-contain">
                            <label rel="tootip" title=<?php echo $minrelaytxfee_tt_content; ?> for="minrelaytxfee_id" style="width: 200px !important; 
							       border-width: 0px; display: inline-block;"><strong>Min Relay Tx Fee (satoshis) (1000 - 50000):</strong></label><br />
                            <input type="range" name="minrelaytxfee" id="minrelaytxfee_id" value=<?php echo $minrelaytxfee; ?> min="1000" max="50000" 
							       step="1" data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div>

					<!-- Daily Upload Limit Slider Control -->
                    <div class="ui-field-contain">
                            <label rel="tootip" title=<?php echo $maxuploadtarget_tt_content; ?> for="maxuploadtarget_id" style="width: 200px !important; 
							       border-width: 0px; display: inline-block;"><strong>Daily Upload Limit (MB) (144-5000):</strong></label><br />
                            <input type="range" name="maxuploadtarget" id="maxuploadtarget_id" value=<?php echo $maxuploadtarget; ?> min="144" max="5000" 
							       data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div>

					<!-- Mempool Size Limit Slider Control -->
                    <div class="ui-field-contain">
                            <label rel="tootip" title=<?php echo $maxmempool_tt_content; ?> for="maxmempool_id" style="width: 200px !important; 
							      border-width: 0px; display: inline-block;"><strong>Mempool Size Limit (MB) (100-500):</strong></label><br />
                            <input type="range" name="maxmempool" id="maxmempool_id" value=<?php echo $maxmempool; ?> min="100" max="500" 
							      data-highlight = "true" style="border-width: 0px; display: inline-block;">
                    </div>
					<br />
					Once the settings are updated, a bitcoin restart must be initiated for the changes will take effect.  
					Wait 15 minutes after pressing "Restart Bitcoin" to resume normal operations.
					<br />
					<br />

			   </fieldset>
		    </form>
                    <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                        <input type="submit" name="submit" value="Update Settings" id="update-bitcoin-config" />
	                </div>	
			<br />         
           <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                <input type="button" value=" Restart Bitcoin " id="bitcoin_restart_conf" onClick="bitcoinControl(this);" />
		    </div>

	</div>
</div> 

