<?php
session_start();
require 'autoload.php';
use TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'CpNsoudElmFHaMCgaoXoqrp1h'); 
define('CONSUMER_SECRET', 'lIY1JtAR4BH3MgHxSiF5yAEvPNeMANpGlb8rzsAfh7Fyh599bS'); 
define('OAUTH_CALLBACK', 'https://floating-wave-21421.herokuapp.com/callback.php'); 

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
	$request_token = [];
	$request_token['oauth_token'] = $_SESSION['oauth_token'];
	$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);

	$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
	$_SESSION['access_token'] = $access_token;
	// redirect user back to index page
	header('Location: home.php');
}
