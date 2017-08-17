<?php
	session_start();

	require 'autoload.php';
	use TwitterOAuth\TwitterOAuth;
	include("ahtml.php");

	define('CONSUMER_KEY', 'CpNsoudElmFHaMCgaoXoqrp1h'); 
	define('CONSUMER_SECRET', 'lIY1JtAR4BH3MgHxSiF5yAEvPNeMANpGlb8rzsAfh7Fyh599bS'); 
	define('OAUTH_CALLBACK', 'https://floating-wave-21421.herokuapp.com/callback.php'); 

	$user = $_SESSION['user'];	

	$access_token = $_SESSION['access_token'];
	$conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		

	$followers = $conn->get('followers/list',["screen_name" =>$user->screen_name]);

	 // https://api.twitter.com/1.1/search/tweets.json?q=%23superbowl
		
	$temp = [];
	foreach ($followers->users as $res) {
		$t=[];
		$t['name']=$res->name;
		$t['screen_name']=$res->screen_name;
		$temp[]=$t;
		
	}
	echo json_encode($temp);
