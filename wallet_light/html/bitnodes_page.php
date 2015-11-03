	<div data-role="content" class="content">
		<div style="margin-top:15px;margin-left:5px;">
			<span id="span_WalletHeaderText">Bitnodes</span>
	   		<hr class="hr_wallet">
	    </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Bitnodes Status:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php print $bitnode['status']; ?>&nbsp;</span></div>
	       </div>
	    </div>

        <div class="div_WalletOverview">
           <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Bitnodes Verified:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php print $bitnode['verified']; ?>&nbsp;</span></div>
           </div>
        </div>

	    <div class="div_WalletOverview">
          <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Performance Index:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php echo '<a href="https://getaddr.bitnodes.io/nodes/leaderboard/?q='.$extip.'">Ratings for '.$extip.' </a>'; ?>&nbsp;</span></div>
          </div>
        </div>
        <br /> 
        <div class="div_WalletOverview">
          <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Bitnodes Stats:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php echo '<a href="https://getaddr.bitnodes.io/nodes/'.$extipport.'">Stats for '.$extip.'</a>'; ?>&nbsp;</span></div>
	      </div>
        </div>
	</div> <!-- Content -->

