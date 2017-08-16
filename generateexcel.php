<?php

$header = "Country" . "\t";
$header .= "Network" . "\t";
$header .= "MCC" . "\t";
$header .= "MNC" . "\t";
$header .= "ClientPrice" . "\t";
$header .= "Currency" . "\t";



header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=expot.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header"; 
exit();