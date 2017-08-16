<?php 
session_start();
require 'vendor/autoload.php';
// include autoloader
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$html="<table border=1>
<tr>
<td>hello</td><td>world</td>
</tr>
</table>";
// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
file_put_contents("tweets.pdf", $dompdf->output("tweets.pdf"));

$_SESSION['pdf']="done";

header("Location: home.php");

