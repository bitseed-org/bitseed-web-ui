	<div data-role="content" class="content">

	    <div style="margin-top:15px;margin-left:5px;">
		  <span id="span_WalletHeaderText">Bitcoin & Device Control</span>
	   	  <hr class="hr_wallet">
	    </div>
	   
        <div class="ui-content">
		 <span class="status_title">Status</span><br />
		 <span class="status_text" id="bitcoin_status"></span>
            <hr class="hr_secondary_wallet">
             <!-- <form name="bitcoinconf_form" id="bitcoinconf_form" action="php/bitcoin_conf.php"> -->
             <form name="bitcoinconf_form" id="bitcoinconf_form" action="php/www_wr_bconf_mbox.php" method="post">
		        <fieldset class="ui-field-contain">
					<div class="ui-input-text" style="width: 200px !important; border-width: 0px; display: inline-block;">
                        <label for="max_peers" style="display: inline-block"><h3>Max Connections:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; display: inline-block;">
                        <input type="number" name="max_peers" id="max_peers" min="1" max="125" value=<?php echo $max_peers ?> placeholder="125"/>
				    </div>
					<div class="ui-input-text" style="width: 200px !important; border-width: 0px; display: inline-block;"> 
                        <label for="minrelaytxfee"><h3>Min Relay Tx Fee:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; display: inline-block;">
                        <input type="number" step="any" name="minrelaytxfee" id="minrelaytxfee" min=".000001" max=".1" value=<?php echo $minrelaytxfee ?> placeholder=".00001000"/>
					</div>
					<div class="ui-input-text" style="width: 200px !important; border-width: 0px; display: inline-block;">
                        <label for="limitfreerelay"><h3>Limit Free Relay:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; margin: 0px; display: inline-block;">
                        <input type="number" name="limitfreerelay" id="limitfreerelay" min="1" max="25" value=<?php echo $limitfreerelay ?> placeholder="15"/>
					</div>
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

