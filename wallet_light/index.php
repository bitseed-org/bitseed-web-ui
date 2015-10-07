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

// Populate values from bitcoin.conf - had some trouble here.  hack in place -
// Need to come back to.
// $conf_values = shell_exec('python/parse-btconf.py 2>&1');
// $conf_stats = json_decode($conf_values, TRUE);

$extip = file_get_contents('/home/linaro/extip');
$extip = trim($extip);
$extipport = $extip . "-8333";
$concensusblock = file_get_contents('https://blockchain.info/q/getblockcount');
$address = file_get_contents('/home/linaro/reward-addr');

$jsnaddr = "https://getaddr.bitnodes.io/api/v1/nodes/{$extip}-8333";
$bitnodejson = file_get_contents($jsnaddr);
$bitnode = json_decode($bitnodejson, TRUE);

$serial = file_get_contents('/var/www/html/serial');
$bitseedvers = file_get_contents('/home/linaro/version');
$wallet = new PhpBitAdmin_Wallet();
$chaininfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getblockchaininfo') ;
$meminfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getmempoolinfo') ;
$netinfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getnetworkinfo') ;
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
$check_login = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getinfo') ;
if ( !is_array($check_login) ) {
die (' At startup, Bitcoin requires 10-15 minutes to check its database and the web UI can be active. Please wait 10-15 minutes. If the web UI never responds, check that the RPC Username and Password are tha same in ~.bitcoin/bitcoin.conf and /var/www/html/config.inc.php are the same');
}
}
?>

<?php
	global $param, $max_peers, $minrelaytxfee, $limitfreerelay;
    $fh_2 = fopen ("/home/linaro/bconf", "r") or die ("Unable to open bconf file");
	if ($fh_2) {
        while (($line = fgets($fh_2)) !== false) {
            $param = explode("=", $line);				 
			switch ($param[0]) {
                case "maxconnections":
				    $max_peers=$param[1];
					break;
                case "minrelaytxfee":
				    $minrelaytxfee=$param[1];
					break;
                case "limitfreerelay":
				    $limitfreerelay=$param[1];
					break;
			}
		}
		fclose($fh_2);
	}
?>
<?php $konn = 20 ?>    
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

$(document).bind("pagecreate", function () {
$.mobile.ajaxEnabled = false;
});
</script>

<script>
// The code below is currently not working.  It works fine when buttons are 
// attached to functions, but fails on a document load event.  
$(document).ready (function(){
    // Read in the bitcoin.conf file and extract
    // the values for:
	//  1.  max-peers
	//  2.  minrelaytxfee
	//  3.  limitfreerelay

	// Make an Ajax call to the server to parse bitcoin.conf
	// and populate the values in the controls page.
    // var r=false;
    // r = confirm("Are you sure you want to update the bitcoin software?"); 	

	// if (r == false) {
	//     return;
	// }

    var request = $.ajax({
         url: "php/jquery-ajax.php",
         type: "GET",
         dataType: "html",
		 data: { 
         id: domvalue
			},
    });

	
    request.done(function(msg) {
         $("#bitcoin_status").html(msg);          
    });

    request.fail(function(jqXHR, textStatus) {
         alert( "Request failed: " + textStatus );
    });
    
});
</script>

<script>
    function bitcoinControl(e) {
        var domvalue = $(e).attr('id');
        var r=false;

        switch (domvalue) {
            case "bitcoin_restart":
                r = confirm("Are you sure you want to restart the device?");
                break;
           case "update-software":
                r = confirm("Are you sure you want to update the bitcoin software?");
                break;
        }
        if (r == false) {
            return;
        }
        var request = $.ajax({
            url: "php/jquery-ajax.php",
            type: "GET",
            dataType: "html",
            data: {
                id: domvalue
            },
       });

       request.done(function(msg) {
           $("#bitcoin_status").html(msg);
       });

       request.fail(function(jqXHR, textStatus) {
           alert( "Request failed: " + textStatus );
       });
}
</script>
<script>

    function deviceControl(e) {
		var domvalue = $(e).attr('id'); 
        var r = false;

		switch (domvalue) {
		    case "device_restart":
		        r = confirm("Are you sure you want to restart the device?"); 	
				break;
		    case "device_shutdown":
		        r = confirm("Are you sure you want to shutdown the device?"); 	
				break;
		}
		if (r == false) {
		    return;
		}

        var request = $.ajax({
            url: "php/jquery-ajax.php",
            type: "GET",
            dataType: "html",
			data: { 
                id: domvalue
			},
        });

        request.done(function(msg) {
            $("#bitcoin_status").html(msg);          
        });

        request.fail(function(jqXHR, textStatus) {
            alert( "Request failed: " + textStatus );
        });
    }
</script>

<style>
input.normal {
    width: 45px !important;
    display: inline-block; }
</style>

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
                        <div class="ui-block-a"><span class="primary">Node Type:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php if ($chaininfo['pruned'] === false) {print "Full Node";} else {print "Pruned";} ?></span></div>
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
                        <div class="ui-block-a"><span class="primary">Tx in Mempool:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php print $meminfo['size']; ?></span></div>
                   </div>
                </div>

		<div class="div_WalletOverview">
                   <div class="ui-grid-a">
                        <div class="ui-block-a"><span class="primary">Mempool Bytes:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php print $meminfo['bytes'] .  bytes"; ?></span></div>
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
               		<div class="ui-block-a"><span class="primary">Public IP Address:</span></div>
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
                        <div class="ui-block-a"><span class="primary">Device ID:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php print $serial; ?></span></div>
                   </div>
                </div>

		<div class="div_WalletOverview">
                   <div class="ui-grid-a">
                        <div class="ui-block-a"><span class="primary">Device Version:</span></div>
                        <div class="ui-block-b"><span class="secondary_light"><?php print $bitseedvers; ?></span></div>
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

	<div class="div_WalletOverview">
                   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Performance Index:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php echo '<a href="https://getaddr.bitnodes.io/nodes/leaderboard/?q='.$extip.'">Ratings for '.$extip.' </a>'; ?>&nbsp;</span></div>
        </div>
        </div>
        
        <div class="div_WalletOverview">
                   <div class="ui-grid-a">
             <div class="ui-block-a"><span class="primary">Bitnodes Stats:</span></div>
             <div class="ui-block-b"><span class="secondary_light"><?php echo '<a href="https://getaddr.bitnodes.io/nodes/'.$extipport.'">Stats for '.$extip.'</a>'; ?>&nbsp;</span></div>
	</div>
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
	   
        <div class="ui-content">
		 <h3><span id="bitcoin_status">Bitcoin Controls</span></h3>
            <!-- <form name="bitcoinconf_form" id="bitcoinconf_form" method="POST" action="php/bitcoin_conf.php"> -->
        <!--    <form name="bitcoinconf_form" id="bitcoinconf_form" action="php/bitcoin_conf.php">
		        <fieldset class="ui-field-contain">

                    
					<div class="ui-input-text" style="width: 200px !important; border-width: 0px; display: inline-block;">
                        <label for="max_peers" style="display: inline-block"><h3>Max Connections:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; display: inline-block;">
                        <input type="number" name="max_peers" id="max_peers" min="0" max="125" value=<?php echo $max_peers ?> placeholder="125"/>
				    </div>
					<div class="ui-input-text" style="width: 200px !important; border-width: 0px; display: inline-block;"> 
                        <label for="minrelaytxfee"><h3>Min Relay Tx Fee:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; display: inline-block;">
                        <input type="number" step="any" name="minrelaytxfee" id="minrelaytxfee" value=<?php echo $minrelaytxfee ?> placeholder=".00001000"/>
					</div>
					<div class="ui-input-text" style="width: 200px !important; border-width: 0px; display: inline-block;">
                        <label for="limitfreerelay"><h3>Limit Free Relay:</h3></label>
					</div>
					<div class="ui-input-text" style="width: 100px !important; border-width: 0px; margin: 0px; display: inline-block;">
                        <input type="number" name="limitfreerelay" id="limitfreerelay" value=<?php echo $limitfreerelay ?> placeholder="0"/>
					</div>
                    <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                        <input type="submit" name="submit" value="Update bitcoin.conf" id="update-bitcoin-config" />
	                </div>	
			   </fieldset>
		    </form>  -->
            <hr class="hr_secondary_wallet">
            <?php 
                // Open Updateflag to see if there is a '1'.  If there is, then write a message to the
                // user that new software is available.    
                $fh=fopen("/home/linaro/updateflag", "r") or die ("Unable to open updateflag file");
                $line = fgets($fh);
                if (preg_match("/^1/", $line)) {
                    print "<h3>Software updates are available</h3>";
                }
                fclose($fh);
            ?>
            <!-- <h3>Software updates are available</h3> -->
         
            <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                <input type="button" value="Update software" id="update-software" onClick="bitcoinControl(this);" />
	        </div>
            <hr class="hr_secondary_wallet">
            <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
                <input type="button" value=" Restart Bitcoin " id="bitcoin_restart" onClick="bitcoinControl(this);" />
		    </div>
            <div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
			    <input type="button" value="Shutdown Bitseed" id="device_shutdown" onClick="deviceControl(this);" />
		    </div>
		    <!-- <hr class="hr_secondary_wallet"> -->
		    <!-- <hr class="hr_secondary_wallet"> -->
	</div>
<!--   </div>
		</div> -->
</div>
<script>
// -----------------------------------------------------------------
//  This is the Ajax call for the bitcoin.conf updates to transfer 
//  data to the server-side.  The form elements are driectly above.
//  NOTE:  Can I put this in the header with the document.ready() function?
// -----------------------------------------------------------------
$("#bitcoinconf_form").submit(function( e ) {
	 e.preventDefault();
	 var postData = $("#bitcoinconf_form").serialize();
	 var formURL = $(this).attr("action");
	 var request = $.ajax({
         url: formURL,
         type: 'POST',
         dataType: "html",
         data: postData 
     });
     request.done(function(msg) {
        $("#bitcoin_status").html(msg);          
     });
     request.fail(function(jqXHR, textStatus) {
        alert( "Request failed: " + textStatus );
     });
});
// -----------------------------------------------------------------
</script>
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
