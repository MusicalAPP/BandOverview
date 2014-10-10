<?php


// index.php
if (isset($_GET['search'])) {

 	require_once('templates/TwitterAPIExchange.php');

	$settings = array(
	    'oauth_access_token' => "YOURSETTINGSHERE",
	    'oauth_access_token_secret' => "YOURSETTINGSHERE",
	    'consumer_key' => "YOURSETTINGSHERE",
	    'consumer_secret' => "YOURSETTINGSHERE"
	);

 	$artist = $_GET['search'];
 	$artistspotify = str_replace(" ", "&20", $artist);
 	$artistgoogle = str_replace(" ", "+", $artist);
 	$spotify = file_get_contents("https://api.spotify.com/v1/search?q=$artistspotify&type=artist");
 	$spotify_json = json_decode($spotify, true);
 	//Worst way to get twitter feed from search results
	$headers = get_headers("http://www.google.com/search?hl=en&q=$artistgoogle+twitter&btnI=1", 1);
	$url = $headers['Location'];

	$twittername = substr(strrchr($url, "/"), 1);

	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?screen_name=' . $twittername . '&count=10';
	$requestMethod = 'GET';
	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		    ->buildOauth($url, $requestMethod)
		    ->performRequest();

	$twitter_string = json_decode($response,true);
			    
	include 'templates/main.php';
}
else
{
	
	require 'templates/land.php';

}

?>