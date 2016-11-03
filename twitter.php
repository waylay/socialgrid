<?php
// Twitter
require 'config.php';
require 'twitter-api-php/TwitterAPIExchange.php';
//meetrichpearson
$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?screen_name=jimmywhite147&include_entities=true&count=20';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($config['twitter']);
$response = json_decode($twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest());

// Get only tweets with images
$response = array_filter($response, function($tweet){
  return isset($tweet->entities->media);
});

foreach ($response as $project) {
 // Project data
 $post['date'] = strtotime($project->created_at);
 $post['title'] = $project->text;
 $post['image'] = $project->entities->media[0]->media_url;
 $post['category'] = implode(", ", $project->entities->hashtags);
 $post['url'] = $project->entities->media[0]->display_url;
 $posts[] = $post;
}
return $posts;
