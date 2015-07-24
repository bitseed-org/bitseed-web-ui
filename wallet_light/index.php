<?php 
require_once('config.inc.php'); 
require('php/phpbitadmin.class.php');

// Internal IP, MAC address
// $inet_mac_values = shell_exec('/home/linaro/internal-ip-mac.py 2>&1');
$inet_mac_values = shell_exec('python/internal-ip-mac.py 2>&1');
$inet_mac_addr = json_decode($inet_mac_values, TRUE);

// Disk Usage, RAM Usage, CPU Load and Uptime status
// $device_values = shell_exec('/home/linaro/disk-info.py 2>&1');
$device_values = shell_exec('python/disk-info.py 2>&1');
$device_stats = json_decode($device_values, TRUE);

$extip = file_get_contents('http://ipecho.net/plain');
$concensusblock = file_get_contents('https://blockchain.info/q/getblockcount');
$address = file_get_contents('/home/linaro/reward-addr');

$jsnaddr = "https://getaddr.bitnodes.io/api/v1/nodes/{$extip}-8333";
$bitnodejson = file_get_contents($jsnaddr);
$bitnode = json_decode($bitnodejson, TRUE);

$serial = file_get_contents('/var/www/html/serial');
$chaininfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getblockchaininfo') ;
$meminfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getmempoolinfo') ;
$netinfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getnetworkinfo') ;

$wallet = new PhpBitAdmin_Wallet();
if ( (empty($_SESSION['PHPBITADMIN'])) || ($_SESSION['PHPBITADMIN'] === null) ) { // check if $_SESSION is set.
	$session = $wallet->setSession($scheme, $server_ip, $server_port, $rpc_user, $rpc_pass, $btc_addr, $p_phrase);
} else {
	$session = true;
}
if( $session ) {
	$check_server = $wallet->ping($scheme, $server_ip, $server_port);
	if ( $check_server == '' || empty($check_server) ) {
		die (' The bitcoind server located at '. $scheme.'://'.$server_port.' on Port:['.$server_port.'] appears to be unresponsive.');
	}
	$check_login =  $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getinfo') ;
	if ( !is_array($check_login) ) {
		die (' At startup, Bitcoin requires 10-15 minutes to check its database and the web UI can be active.  Please wait 10-15 minutes. If the web UI never responds, check that the RPC Username and Password are tha same in ~.bitcoin/bitcoin.conf and /var/www/html/config.inc.php are the same');
	}
}
?>
<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bitcoin Node</title>
<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css" />
<link rel="stylesheet" href="css/m_phpbitadmin.css" />
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">

// KD - This needs to change to AJAX True    
$(document).bind("pagecreate", function () {
    $.mobile.ajaxEnabled = false;
});
</script>

<!-- AJAX calls for system control at the server -->
<script>

    function myCall(e) {
		var domvalue = $(e).attr('id'); 
        var request = $.ajax({
            url: "php/jquery-ajax.php",
            type: "GET",
            dataType: "html",
			data: { 
                id: domvalue
			},
        });

        request.done(function(msg) {
            $("#bitcoin-status").html(msg);          
        });

        request.fail(function(jqXHR, textStatus) {
            alert( "Request failed: " + textStatus );
        });
    }
</script>

</head>
<body>

<div data-role="page" id="home" data-theme="a">

    <div class="panel left" data-role="panel" data-position="left" data-display="push" id="mypanel">
        <div id = "menu">
	            <ul>
   		            <li><a href="#home" title="title">Home</a></li><br />
		            <li><a href="#device-status" title="Device Status">Device Status</a></li><br /> 
		            <li><a href="#bitnodes">Bitnodes</a></li><br />
		            <li><a href="#controls">Controls</a></li>
		       </ul>
	    </div>
	 </div>

     <div data-role="header"> 
		<span class="open left"><a href="#mypanel" data-role="navbar"><img src="images/nav-icon-blue.png" height="20" width="20" alt=""/></a></span>
		<span id="span_TitleHeader">&nbsp; &nbsp; <img src="images/bitcoind.png" height="32" width="32" alt=""/>&nbsp;Bitseed Bitcoin Node</span>
	</div><!-- /header -->

	<div data-role="content" class="content">

		<div style="margin-top:15px;margin-left:5px;">
			<span id="span_WalletHeaderText">Bitcoin Node Status</span>
			<hr class="hr_wallet">
		</div>
	
 		<div class="div_WalletOverview">
                   <div class="ui-grid-a">
                        <div class="ui-block-a"><span class="primary">Bitcoin Core Version:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php print $netinfo['subversion']; ?></span></div>
                   </div>
                </div>

		<div class="div_WalletOverview">
		   <div class="ui-grid-a">
		       <div class="ui-block-a"><span class="primary">Device at Block:</span></div>
			   <div class="ui-block-b"><span class="secondary_light"><?php print $check_login['blocks']; ?>&nbsp;</span></div>
		   </div>
		</div>
		
		 <div class="div_WalletOverview">
		   <div class="ui-grid-a">
			<div class="ui-block-a"><span class="primary">Network Block:</span></div>
			<div class="ui-block-b"><span class="secondary_light"><?php print_r ($concensusblock); ?></span></div>
		   </div>
		</div>
		
		<div class="div_WalletOverview">
		   <div class="ui-grid-a">
			<div class="ui-block-a"><span class="primary">Peer Connections:</span></div>
			<div class="ui-block-b"><span class="secondary_light"><?php print $check_login['connections']; ?></span></div>
		   </div>
		</div>
		
		 <div class="div_WalletOverview">
                   <div class="ui-grid-a">
                        <div class="ui-block-a"><span class="primary">Transactions in Mempool:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php print $meminfo['size']; ?></span></div>
                   </div>
                </div>

 		<div class="div_WalletOverview">
                   <div class="ui-grid-a">
                        <div class="ui-block-a"><span class="primary">Minimum Relay Fee:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php printf ('%.08lf', $check_login['relayfee']); ?></span></div>
                   </div>
                </div>

                <div class="div_WalletOverview">
                   <div class="ui-grid-a">
                        <div class="ui-block-a"><span class="primary">Node Type:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php if ($chaininfo['pruned'] === false) {print "Full Node";} else {print "Pruned";} ?></$
                   </div>
                </div>
                
		<div class="div_WalletOverview">
		   <div class="ui-grid-a">
			<div class="ui-block-a"><span class="primary">Device ID:</span></div>
			<div class="ui-block-b"><span class="secondary_light"><?php print $serial; ?></span></div>
		   </div>
		</div>
		
		<div class="div_WalletOverview">
		   <div class="ui-grid-a">
            <div class="ui-block-a"><span class="primary">BTC Balance:</span></div>
            <div class="ui-block-b"><span class="secondary_light"><?php print $check_login['balance']; ?>&nbsp;</span></div>
		   </div>
        </div>
        
        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
               <div class="ui-block-a"><span class="primary">Node Public IP Address:</span></div>
               <div class="ui-block-b"><span class="secondary_light"><?php print $extip; ?>&nbsp;</span></div>
		   </div>
        </div>		

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Internal IP Address:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php print $inet_mac_addr['inet_address']; ?>&nbsp;</span></div>
		   </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
              <div class="ui-block-a"><span class="primary">Mac Address:</span></div>
              <div class="ui-block-b"><span class="secondary_light"><?php print $inet_mac_addr['mac_address']; ?>&nbsp;</span></div>
		   </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
              <div class="ui-block-a"><span class="primary">Last blockchain Backup:</span></div>
              <div class="ui-block-b"><span class="secondary_light"><?php print $inet_mac_addr['db_date']; ?>&nbsp;</span></div>
		   </div>
        </div>
        
        <div class="div_WalletOverview">
		    <div class="ui-grid-a"> 
            <span class="primary">Donate Bitcoin:</span>
            <span class="secondary_light_donate"><?php print $address; ?>&nbsp;</span>
		   </div>
        </div>
        
    </div><!-- content -->

</div>  <!-- page -->

<div data-role="page" id="device-status" data-theme="a">
    <div class="panel left" data-role="panel" data-position="left" data-display="push" id="mypanel">
        <div id = "menu">
	            <ul>
   		            <li><a href="#home" title="title">Home</a></li><br />
		            <li><a href="#device-status" title="Device Status">Device Status</a></li><br />
		            <li><a href="#bitnodes">Bitnodes</a></li><br />
		            <li><a href="#controls">Controls</a></li>
		       </ul>
	    </div>
	 </div>

     <div data-role="header"> 
		<span class="open left"><a href="#mypanel" data-role="navbar"><img src="images/nav-icon-blue.png" height="20" width="20" alt=""/></a></span>
		<span id="span_TitleHeader">&nbsp; &nbsp; <img src="images/bitcoind.png" height="32" width="32" alt=""/>&nbsp;Bitseed Bitcoin Node</span>
	</div>

	<div data-role="content" class="content">
	   
		<div style="margin-top:15px;margin-left:5px;">
			<span id="span_WalletHeaderText">Device Status</span>
			<hr class="hr_wallet">
		</div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
               <div class="ui-block-a"><span class="primary">Disk Size</span></div>
               <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['disk_size']; ?>&nbsp;</span></div>
        </div>
        </div>
        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Disk Space Used:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['disk_used']; ?>&nbsp;</span></div>
        </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Disk Space Available:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['disk_avail']; ?>&nbsp;</span></div>
        </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">RAM Used:</span></div>
              <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['ram_used']; ?>&nbsp;</span></div>
        </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">RAM Free:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['ram_free']; ?>&nbsp;</span></div>
        </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
              <div class="ui-block-a"><span class="primary">CPU Load:</span></div>
              <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['load']; ?>&nbsp;</span></div>
        </div>
        </div>

        <div class="div_WalletOverview">
		   <div class="ui-grid-a">
              <div class="ui-block-a"><span class="primary">Uptime:</span></div>
              <div class="ui-block-b"><span class="secondary_light"><?php print $device_stats['uptime']; ?>&nbsp;</span></div>
        </div>
        </div>

		
	 </div><!-- /content  -->
</div> <!-- Page -->

<div data-role="page" id="bitnodes" data-theme="a">

    <div class="panel left" data-role="panel" data-position="left" data-display="push" id="mypanel">
        <div id = "menu">
	            <ul>
   		            <li><a href="#home" title="title">Home</a></li><br />
		            <li><a href="#device-status" title="Device Status">Device Status</a></li><br />
		            <li><a href="#bitnodes">Bitnodes</a></li><br />
		            <li><a href="#controls">Controls</a></li>
		       </ul>
	    </div>
	 </div>

     <div data-role="header"> 
		<span class="open left"><a href="#mypanel" data-role="navbar"><img src="images/nav-icon-blue.png" height="20" width="20" alt=""/></a></span>
		<span id="span_TitleHeader">&nbsp; &nbsp; <img src="images/bitcoind.png" height="32" width="32" alt=""/>&nbsp;Bitseed Bitcoin Node</span>
	</div>

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
        
	</div>
</div>
		
<div data-role="page" id="controls" data-theme="a">

    <div class="panel left" data-role="panel" data-position="left" data-display="push" id="mypanel">
        <div id = "menu">
	            <ul>
   		            <li><a href="#home" title="title">Home</a></li><br />
		            <li><a href="#device-status" title="Device Status">Device Status</a></li><br />
		            <li><a href="#bitnodes">Bitnodes</a></li><br />
		            <li><a href="#controls">Controls</a></li>
		       </ul>
	    </div>
	 </div>

     <div data-role="header"> 
		<span class="open left"><a href="#mypanel" data-role="navbar"><img src="images/nav-icon-blue.png" height="20" width="20" alt=""/></a></span>
		<span id="span_TitleHeader">&nbsp; &nbsp; <img src="images/bitcoind.png" height="32" width="32" alt=""/>&nbsp;Bitseed Bitcoin Node</span>
	</div>
	<div data-role="content" class="content">
	   
		<div style="margin-top:15px;margin-left:5px;">
			<span id="span_WalletHeaderText">Controls</span>
			<hr class="hr_wallet">
		</div>
        <!-- <div class="ui-content">
            <form method="get" action="">
		        <fieldset class="ui-field-contain">
                    <label for="age"><h3>Set Max Peer Connections:</h3></label>
                    <input type="number" name="age" id="age" value="" placeholder="125"/>
			    </fieldset>
		</div> -->
        <div class="ui-content">
            <form method="get" action="">
		        <fieldset class="ui-field-contain">
                    <label for="max-peers"><h3>Set Max Peer Connections:</h3></label>
                    <input type="number" name="max-peers" id="max-peers" value="" placeholder="125"/>
			    </fieldset>
		</div>
        <div data-role="controlgroup" data-type="horizontal">
		    <h3>Bitcoin Controls</h3>
			<!-- <button class="ui-btn" id="bitcoin-enable" data-role="button">Enable</button> -->
			<!-- <button class="ui-btn" id="bitcoin-disable">Disable</button> -->
			<!-- <button class="ui-btn">Restart</button> -->
			<!-- <input type="button" value = "Enable" onclick="button_enable_clicked()" /> -->
			<!-- <input type="button" value = "Disable" onclick="button_disable_clicked()" /> -->
			<!-- <input type="button" value = "Restart" onclick="button_restart_clicked()" /> -->
            <input type="button" value="Enable" id="bitcoin_enable" onClick="myCall(this);" />
            <input type="button" value="Disable" id="bitcoin_disable" onClick="myCall(this);" />
            <input type="button" value="Restart" id="bitcoin_restart" onClick="myCall(this);" />

		</div>
		<h3>Device Controls</h3>
        <div data-role="controlgroup" data-type="horizontal">
			<!-- <input type="button" value = "Restart" onclick="button_device_restart_clicked()" /> -->
			<!-- <input type="button" value = "Shutdown" onclick="button_device_shutdown_clicked()" /> -->
			<input type="button" value="Shutdown" id="device_shutdown" onClick="myCall();" />
            <input type="button" value="Restart" id="device_restart" onClick="myCall();" />

	    </div>
	</div>
</div>

<div data-role="footer" data-id="main" data-position="fixed" data-tap-toggle="false">
	<div data-role="navbar">
		<ul>
		    <!--	<li><a href="transactions.php" data-ajax="false" data-icon="bullets">Transactions</a></li> -->
		    <!--		<li><a href="settings.php" data-ajax="false" data-icon="gear">Settings</a></li>  -->
			<li><a href="index.php" data-ajax="false" data-icon="delete">Log Out</a></li>
		</ul>
	</div>
</div><!-- /footer -->

<!-- </div><!-- /page home -->
</body>
</html>
<?php ob_flush(); ?>

                <div class="div_WalletOverview">
                        <span class="primary">CPU Load:</span>
                        <span class="secondary_light"><?php print $bitnode['verified']; ?>&nbsp;</span>
                </div>

                <div class="div_WalletOverview">
                        <span class="primary">Uptime:</span>
                        <span class="secondary_light"><?php print $bitnode['verified']; ?>&nbsp;</span>
                </div>

		
	</div><!-- /content  -->
		
	<div data-role="footer" data-id="main" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar">
			<ul>
			<!--	<li><a href="transactions.php" data-ajax="false" data-icon="bullets">Transactions</a></li>
				<li><a href="settings.php" data-ajax="false" data-icon="gear">Settings</a></li>  -->
				<li><a href="index.php" data-ajax="false" data-icon="delete">Log Out</a></li>
			</ul>
		</div>
	</div><!-- /footer -->
		
<!-- </div> /page home -->
</body>
</html>
<?php ob_flush(); ?>
