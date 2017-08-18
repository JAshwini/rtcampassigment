<?php 
session_start();

require 'autoload.php';
use TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'CpNsoudElmFHaMCgaoXoqrp1h'); 
define('CONSUMER_SECRET', 'lIY1JtAR4BH3MgHxSiF5yAEvPNeMANpGlb8rzsAfh7Fyh599bS'); 
define('OAUTH_CALLBACK', 'https://rtcampassignment.herokuapp.com/callback.php'); 

$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$q = $_GET['q'];

$res = $connection->get('users/search',["q"=>$q]);

foreach ($res as $value) {
	echo '<div class="row">';
	echo '<div class="col-lg-2 col-sm-1">';
		echo '<img src="'.$value->profile_image_url.'">';
	echo '</div>';
	echo '<div class="col-lg-10 col-sm-11">
			<label class="search_name" onclick="search_tweets(\''.$value->screen_name.'\',\''.$value->name.'\')">'.$value->name.'</label><br>
			<font class="search_screen_name">'.$value->screen_name.'</font>
		  </div>';
	echo '</div>';
}