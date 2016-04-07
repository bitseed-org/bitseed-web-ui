//  TOOLTIP CONTENTi
//  Replace text in quotes below for tooltip content.
// --------------------------------------------------------------------------

// Disable Wallet
$disablewallet_tooltip = "Bitseed is not intended for storage of bitcoins. If you decide to enable the wallet responsibe for making sure the wallet is properly backed-up and secure.";

// Auto Updates
$autoupdate_tooltip = "When enabled, software updates from Bitseed will be automatically installed";

// Tor Enable
$listenonion_tooltip = "Bitcoin will relay blocks and transactions over the Tor network in addition to the normal IPv4 network.";


// Tor Only Mode
$onlynet_tooltip = "When enabled, bitcoin core will operate exclusively on Tor network. This is the most private option";

// Universal Plug and Play
$upnp_tooltip = "UPnP lets bitcoin open port 8333 on your router to allow incoming connections from other nodes.  This is recomended and will enable more than 8 peer connections";


// Disable Blockchain Backups
$disablebackups_tooltip = "Frees up disk space by disabling the blockchain backup.";

// Min Relay Tx Fee
$minrelaytxfee_tooltip = "Transactions with a fee below this amount will not be relayed by this node.";

// Daily Upload Limit
$maxuploadtarget_tooltip = "Limits how much blockchain data this node will upload to other nodes each day";

// Mempool Size Limit
$maxmempool_tooltip = "Sets the amount of RAM memory to be used for storing unconfirmed transations (the mempool)";

// ------------------------------------------------------------
// Form finished content to place into tooltip.  
// DO NOT WRITE BELOW THIS LINE.
// ------------------------------------------------------------
$disablewallet_tt_content ="'" . $disablewallet_tooltip . "'";
$autoupdate_tt_content ="'" . $autoupdate_tooltip . "'";
$listenonion_tt_content ="'" . $listenonion_tooltip . "'";
$onlynet_tt_content ="'" . $onlynet_tooltip . "'";
$upnp_tt_content ="'" . $upnp_tooltip . "'";
$disablebackups_tt_content ="'" . $disablebackups_tooltip . "'";
$minrelaytxfee_tt_content ="'" . $minrelaytxfee_tooltip . "'";
$maxuploadtarget_tt_content ="'" . $maxuploadtarget_tooltip . "'";
$maxmempool_tt_content ="'" . $maxmempool_tooltip . "'";
// ------------------------------------------------------------
?>
