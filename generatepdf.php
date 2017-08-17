<?php 
session_start();
require('./assets/lib/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$title="Tweets of ".$_SESSION['user_name'];
$pdf->Cell(180,10,$title,0,1,'C');
$pdf->Ln();
$tweets=$_SESSION['tweets'];
foreach ($tweets as $tweet) {
	$pdf->Cell(20,10,$tweet->text);
	$pdf->Ln();
}
$pdf->Output("./assets/pdfs/tweets_".$_SESSION['user_screen_name'].".pdf",'F');

$_SESSION['pdf']="done";

header("Location: home.php");

