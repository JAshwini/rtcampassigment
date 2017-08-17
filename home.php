<?php 
session_start();
if(!isset($_SESSION)){
	header("Location: index.php");
}

require 'autoload.php';
use TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'CpNsoudElmFHaMCgaoXoqrp1h'); 
define('CONSUMER_SECRET', 'lIY1JtAR4BH3MgHxSiF5yAEvPNeMANpGlb8rzsAfh7Fyh599bS'); 
define('OAUTH_CALLBACK', 'https://rtcampassignment.herokuapp.com/callback.php'); 

if (!isset($_SESSION['access_token'])) {
	connect();
} else {
	tweets_and_follower();
	if(!isset($_SESSION['pdf']) || $_SESSION['pdf']!='done'){
		header("Location: generatepdf.php");
	}
}
?>

<?php 
function connect()
{
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

	header('Location: '.$url);
}
function tweets_and_follower()
{
	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$_SESSION['connection'] = $connection;

	$user = $connection->get("account/verify_credentials");

	$_SESSION['user'] = $user;
	$_SESSION['user_name'] = $user->name;
	$_SESSION['user_screen_name'] = $user->screen_name;

	$tweets = $connection->get('statuses/user_timeline',["count" =>10]);
	$_SESSION['tweets']=$tweets;

	$followers = $connection->get('followers/list',["screen_name" =>$user->screen_name, "count"=>10]);
	$_SESSION['followers']=$followers;
}
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
					<li class="active"><a href="#">Home</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="" id="logout"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
				</ul>
			</div>
		</nav>
		<div class="row">
			<div class="col-lg-8 col-sm-12">
				<div class="slider">
					<?php 
					$tweets=$_SESSION['tweets'];
					$cntr=count($tweets);
					if($cntr>0){
					?>
					<div>
						<h3>Hi <?php echo $_SESSION['user_name']; ?>, welcome to tweets world!</h3><br>
					</div>
					<ul class="bxslider">
						<?php 

						foreach ($tweets as $tweet) {
							echo "<li>".$tweet->text."<br></li>";
						} ?>
					</ul>
					<?php } else{
						echo "Tweets Does not exists on your timeline...";
					}
					?>
				</div>
				<?php 
				$filepath = "./assets/pdfs/";
				$filename = "tweets_".$_SESSION['user_screen_name'].".pdf";
				$filepath_csv = "https://rtcampassignment.herokuapp.com/assets/csvs/";
				$filename_csv = "tweets_".$_SESSION['user_screen_name'].".csv";
				?>
				<div class="download_opt" visibility="hidden">
					<div class="g-savetodrive save_to_drive" data-src="<?php echo $filepath."".$filename; ?>" data-filename="<?php echo $filename; ?>" data-sitename="TweeetsWorld"></div>
					<div>
						<center>
							<table>
								<tr>
									<td><a href="<?php echo $filepath."".$filename; ?>" download>Download PDF</a></td>
									<td><label id="csv_download" data="<?php echo $filepath_csv."".$filename_csv; ?>">Download CSV</label></td>
								</tr>
							</table>
						</center>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-sm-12">
				<div><h3>Follwers List</h3></div>
				<input type="text" id="followers_search"/>
				<?php 
				$followers = $_SESSION['followers'];
				foreach ($followers->users as $follower) { ?>
					<div class="row follower_list">
						<div class="col-lg-2 col-sm-1">
							<img src="<?php echo $follower->profile_image_url; ?>">
						</div>
						<div class="col-lg-10 col-sm-11 name">
							<label class="follower_name" data="<?php echo $follower->screen_name; ?>"><?php echo $follower->name; ?></label><br>
							<font class="follower_screen_name"><?php echo $follower->screen_name; ?></font>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		

	</body>


	<script type="text/javascript">
		$(document).ready(function(){
			$('.bxslider').bxSlider();
			$.ajax({
				url: "generatepdf.php",
				success: function () {
					$(".download_opt").attr("visibility","visible");
				}
			})
		});

		$("#logout").click(function () {
			$.ajax({
				url: "logout.php"
			});
		});
		$("#csv_download").click(function () {
			$.ajax({
				url: "generatecsv.php",
				success: function () {
					window.location.href = $(this).attr("data");
				}
			});
			// window.location.href = "generatecsv.php";
		});

		var slider = $('.bxslider').bxSlider({
			mode: 'horizontal'
		});

		$('#reload-slider').click(function(e){
			e.preventDefault();
			$('.bxslider').append('<li><img src="/images/730_200/trees.jpg"></li>');
			slider.reloadSlider();
		});


		$(".follower_name").click(function () {
			var url="tweets.php?screen_name="+$(this).attr("data");
			$.ajax({
				type: "GET",
				url: url,
				dataType: 'html',
				beforeSend: function () {
					$(".slider").html("Wait for a while...");
				},
				success: function (res) {
					$(".slider").html(res);
				},
				error: function (error) {
					console.log(error);
				}
			})
		});
		var followers;
		$.ajax({
	      url: 'followers.php',
	      dataType: 'json',     
	      success: function(json) {
	      	followers=jQuery.parseJSON(JSON.stringify(json));
		    // console.log(followers);
	      },
	      error: function (e) {
	      	console.log(e);
	      }
	    });

	</script>
	</html>