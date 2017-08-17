<?php 
session_start();

require 'autoload.php';
use TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'CpNsoudElmFHaMCgaoXoqrp1h'); 
define('CONSUMER_SECRET', 'lIY1JtAR4BH3MgHxSiF5yAEvPNeMANpGlb8rzsAfh7Fyh599bS'); 
define('OAUTH_CALLBACK', 'https://floating-wave-21421.herokuapp.com/callback.php'); 

$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$screen_name = $_GET['screen_name'];

$tweets = $connection->get('statuses/user_timeline',["screen_name"=>$screen_name,"count" =>10]);
$t = [];
foreach ($tweets as $tweet) {
	 $t[]=$tweet->text;
}
?>
<?php 
$cntr=count($tweets);
if($cntr>0) {?>
<h3>Tweets from <?php print_r($tweets[0]->user->name); ?>'s timeline</h3><br>
<ul class="bxslider">
	<?php 

	foreach ($tweets as $tweet) {
		echo "<li>".$tweet->text."<br></li>";
	} ?>
</ul>
<script type="text/javascript">
	$(document).ready(function(){
			$('.bxslider').bxSlider();
		});

		var slider = $('.bxslider').bxSlider({
			mode: 'horizontal'
		});

		$('#reload-slider').click(function(e){
			e.preventDefault();
			$('.bxslider').append('<li><img src="/images/730_200/trees.jpg"></li>');
			slider.reloadSlider();
		});
</script>
<?php } else{
	echo "Tweets does not exists for the selected Follower...";
}
?>