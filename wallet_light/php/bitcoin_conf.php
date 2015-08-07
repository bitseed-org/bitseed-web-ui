<?php
// if (isset($_POST['max_peers'], $_POST['minrelaytxfee'], $_POST['limitfreerelay'])) {
// echo "values are set"; 		
//}

$max_peers=$_POST['max_peers'];
$minrelaytxfee=$_POST['minrelaytxfee'];
$limitfreerelay=$_POST['limitfreerelay'];



// Open bitcoin.conf and read all values into an array (ordered map)
// $testFile = fopen ("/home/linaro/php-dev/testFile.txt", "w") or die ("Unable to open file!");
// Read in the bitcoin.conf file 
$fh = fopen ("/home/linaro/.bitcoin/bitcoin.conf", "r") or die ("Unable to open file!");
// fwrite ($fh, "test");
$lines = array();
while (!feof($fh)) {
		$lines[] = fgets($fh);
}
fclose($fh);

// for ($i=0; $i<count($lines);$i++) {
// 		echo nl2br($lines[$i]);
// }

// Read in one line at a time.  If the line starts with '#' or if the line is empty, then 
// allow it to pass untouched. 

$commented_lines = array();
$valid_lines = array();
for ($i=0; $i<count($lines);$i++) {
    $line = $lines[$i];
    if (preg_match("/^#/", $line)) {
//	    echo nl2br($lines[$i]);
        $commented_lines[] = $line; 
	}
	else {
	       $valid_lines[] = $line;
    }
}
for ($i=0; $i<count($commented_lines);$i++) {
		// echo nl2br($commented_lines[$i]);
		echo $commented_lines[$i];
}

for ($i=0; $i<count($valid_lines);$i++) {
		// echo nl2br($valid_lines[$i]);
		echo $valid_lines[$i];
}

echo "maxconnections=$max_peers\n";
echo "minrelaytxfee=$minrelaytxfee\n";
echo "limitfreerelay=$limitfreerelay\n";

$finalStr = ob_get_contents();
ob_end_clean();
file_put_contents("/home/linaro/.bitcoin/bitcoin.conf", $finalStr);

echo "bitcoin.conf has been updated";

$file = fopen("/home/linaro/bconf", "w");
echo fwrite ($file, "maxconnections=$max_peers\n");
echo fwrite ($file, "minrelaytxfee=$minrelaytxfee\n");
echo fwrite ($file, "limitfreerelay=$limitfreerelay\n");
fclose($file);


return;
?>
