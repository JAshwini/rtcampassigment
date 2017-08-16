<?php 
session_start();
require('./assets/lib/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output('tweets.pdf','F');

$_SESSION['pdf']='done';
header("location: home.php");