	<div data-role="content" class="content">

	    <div style="margin-top:15px;margin-left:5px;">
		  <span id="span_WalletHeaderText">Bitnodes Registration</span>
	   	  <hr class="hr_wallet">
	    </div>

        <div class="ui-content">
		     <!-- <h3><span id="bitnodes_status">Operation Status:</span></h3> -->
	       <span class="status_title">Status:</span><br />
		   <span class="status_text" id="bitnodes_status"></span>
		   <hr class="hr_secondary_wallet">

           <form name="bitnodes_reg_form" id="bitnodes_reg_form" action="php/bitnodes_reg.php">
		        <fieldset class="ui-field-contain">
			        <div class="ui-input-text" style="width: 150px !important; border-width: 0px; display: inline-block;"> 
                         <div><span class="primary"><h3>Public IP:</h3></span></div>
    			    </div>
			        <div class="ui-input-text" style="width: 150px !important; border-width: 0px; display: inline-block;"> 
                         <div><span class="secondary_light"><?php echo $extip ?></span></div>
			        </div>
					<div class="ui-input-text" style="width: 150px !important; border-width: 0px; display: inline-block;">
                        <label for=""><h3>Web Port:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 150px !important; border-width: 0px; margin: 0px; display: inline-block;">
                        <input type="number" name="extwebport" id="extwebport" min="1" max="1023" value=<?php echo $extwebport ?> placeholder="80"/>
					</div>

					<div style="vertical-align: middle;">
					    <div class="ui-input-text" style="width: 150px !important; vertical-align: middle; border-width: 0px; display: inline-block;">
                            <label for="btc_addr"><h3>BTC Address:</h3></label>
					    </div>
					    <div style="width: 150px !important; vertical-align: middle; border-width: 0px; display: inline-block">
                            <textarea name="btc_addr" id="btc_addr" value=""><?php echo $address; ?></textarea> 
				        </div>
					</div>
					<br />
                    <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                        <input type="submit" name="submit" value="Register for Bitnodes" id="bitnodes_reg" />
	                </div>	
			   </fieldset>
		    </form>
        </div>
	</div> <!-- Content -->

