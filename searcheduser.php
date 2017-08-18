<?php 
session_start();

if(!isset($_SESSION['access_token'])){
	header("Location: index.php");
}

require 'autoload.php';
use TwitterOAuth\TwitterOAuth;
require('./assets/lib/fpdf.php');

define('CONSUMER_KEY', 'CpNsoudElmFHaMCgaoXoqrp1h'); 
define('CONSUMER_SECRET', 'lIY1JtAR4BH3MgHxSiF5yAEvPNeMANpGlb8rzsAfh7Fyh599bS'); 
define('OAUTH_CALLBACK', 'https://rtcampassignment.herokuapp.com/callback.php'); 
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$screen_name = $_GET['sname'];

$tweets = array();
$cnt = 0;
$available=true;

while ($available != false && $cnt!= 10) {
    $tweet= $connection->get('statuses/user_timeline',["screen_name"=>$screen_name,"page"=>$cnt]);
    if (empty($tweet)) {
        $available=false;   
    }
    else {
        foreach ($tweet as $twt) {
            array_push($tweets, $twt);
        }
    }
    $cnt++;
}
// print_r($tweets);die();
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$title="Tweets of ".$_GET['name'];
$pdf->Cell(180,10,$title,0,1,'C');
$pdf->Ln();
$cntr=1;
foreach ($tweets as $tweet) {
	$pdf->Write(10,$cntr.".  ".$tweet->text);
	$pdf->Ln();
	$cntr++;
}
$pdf->Output("./assets/pdfs/tweets_".$_GET['sname'].".pdf",'F');
$filepath = "./assets/pdfs/";
$filename = "tweets_".$_GET['sname'].".pdf";
?>

<!DOCTYPE html>
	<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<link href="assets/lib/jquery.bxslider.css" rel="stylesheet" />
		<link href="assets/css/style.css" rel="stylesheet" />

		<script src="assets/lib/jquery.bxslider.js"></script>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
		<script src="https://apis.google.com/js/platform.js" async defer></script>

	</head>
	<body>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">TweetWorld</a>
				</div>
				<ul class="nav navbar-nav">
					<li class="active"><a href="home.php">Home</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="" id="logout"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
				</ul>
			</div>
		</nav>
		<div style="margin-left: 5%">
			<h3>Tweets of <?php echo $_GET['name']; ?></h3>
			For Downloading pdf <a href="<?php echo $filepath."".$filename; ?>" download>click here</a>
			<ul>
				<?php foreach ($tweets as $tweet) { ?>
					<li><?php echo $tweet->text; ?></li>
				<?php } ?>
			</ul>
		</div>
	</body>

	<script type="text/javascript">
		$("#logout").click(function () {
			$.ajax({
				url: "logout.php"
			});
		});
	</script>
</html>