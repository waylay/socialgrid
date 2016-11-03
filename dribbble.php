<?php
// Dribbble
require 'config.php';
require 'dribbble-php/src/Exception.php';
require 'dribbble-php/src/Client.php';

$dribbble_client = new Dribbble\Client;
$dribbble_client->setAccessToken($config['dribbble']['client_token']);
$posts = array();

try {
    $response = $dribbble_client->makeRequest('/users/meetrichpearson/shots', 'GET');
    foreach ($response as $project) {
      // Project data
      $post['date'] = strtotime($project->created_at);
      $post['title'] = $project->title;
      $post['image'] = $project->images->normal;
      $post['category'] = implode(", ", $project->tags);
      $post['url'] = $project->html_url;
      $posts[] = $post;
    }
} catch (Exception $e) {
    print $e->getMessage();
}
return $posts;
