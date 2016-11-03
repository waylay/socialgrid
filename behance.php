<?php
// Behance
require 'network_api_php/src/ApiException.php';
require 'network_api_php/src/Client.php';


$behance_client_id = $config['behance']['client_id'];
$behance_api = new Behance\Client( $behance_client_id );
$behance_projects = $behance_api->getUserProjects( $config['behance']['user'] );

$posts = array();

foreach ($behance_projects as $project) {
  // Project data
  $post['date'] = $project->published_on;
  $post['title'] = $project->name;
  $post['image'] = $project->covers->original;
  $post['category'] = implode(", ", $project->fields);
  $post['url'] = $project->url;
  $posts[] = $post;

}

return $posts;
