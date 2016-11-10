<?php
// Instagram

//https://instagram.com/oauth/authorize/?client_id=[CLIENT_ID_HERE]&redirect_uri=http://localhost&response_type=token
require 'config.php';
require 'Instagram-PHP-API/src/InstagramException.php';
require 'Instagram-PHP-API/src/Instagram.php';
use MetzWeb\Instagram\Instagram;

$instagram = new Instagram($config['instagram']['client_id']);
$instagram->setAccessToken($config['instagram']['access_token']);

$posts = array();

try {
    $response = $instagram->getUserMedia(181846997,10);
    if($response->meta->code != 400){
      foreach ($response->data as $project) {
        
        // Project data
        $post['date'] = strtotime($project->created_time);
        $post['title'] = $project->caption->text;
        $post['image'] = $project->images->standard_resolution->url;
        $post['category'] = implode(", ", $project->tags);
        $post['url'] = $project->link;
        $posts[] = $post;
      }
    }
} catch (Exception $e) {
    print $e->getMessage();
}
return $posts;
