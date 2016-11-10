<?php

require 'config.php';

class Five00PxPubClient {

	const
		HTTP_METHOD_GET = 'GET',
		HTTP_METHOD_POST = 'POST',
		HTTP_RESPONSE_SUCCESS = 200,
		API_BASE_URL = 'https://api.500px.com';

	var
	  $logger = null,
		$version = 1,
		$defaultParams = array();

	/**
	 * Constructor
	 * @param array $options Initialization options. The array must contain the follow indexes
	 * 'key' => Your 500px Application Key
	 * 'secret' => Your 500px Secret Key
	 * 'version' => The version of the API. Default to 1
	 */
	public function __construct($options) {
	    // Set Logger
	    if ( array_key_exists('logger', $options)
				&& ! empty ($options['logger']) ) {
	        $this->logger = $options['logger'];
	    }

		$this->consumerKey = $options['500px_key'];
		$this->consumerSecret = $options['500px_secret'];
		$this->defaultParams = array(
			'consumer_key' => $this->consumerKey,
			'consumer_secret' => $this->consumerSecret,
		);

		// Set API version
		if ( array_key_exists('version', $options) ) {
			$this->version = $options['version'];
		}
	}

		public static function factory($options=array()) {
			$class = __CLASS__;
			$instance = new $class($options);

			return $instance;
		}

	/**
	 * Log messages
	 */
    private function logMessage() {
		$logger = $this->logger;
		if ($logger == null) {
			return;
		}

		$arg_list = func_get_args();
		call_user_func_array($logger, $arg_list);
	}

	/**
	 * Do an API call on 500px
	 * @param type $apiUrl
	 * @return mixed
	 * @throws Exception
	 */
	public function api($apiUrlCall, $userArgs=array(), $method='GET') {

		// Set up Call Args
		$this->logMessage('Default Args', $this->defaultParams);
		$args = array_merge($userArgs, $this->defaultParams);
		$this->logMessage('API Args', $args);

		$apiUrl = self::API_BASE_URL . '/v' . $this->version . '/' . $apiUrlCall;

		if ($method == self::HTTP_METHOD_GET) {
			$apiUrl .= '?' . http_build_query($args);
		}

		$data = null;
		try {
			$ch = curl_init();

			// Set CURL options
			curl_setopt($ch, CURLOPT_URL, $apiUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			$metadata = curl_getinfo($ch);

			if ( $response === false ) {
				throw new Exception('Invalid Response received');
			}

			// Check Response
			if( array_key_exists('http_code', $metadata)
				&& (int)$metadata['http_code'] == self::HTTP_RESPONSE_SUCCESS ) {
				$data = json_decode($response);
				curl_close($ch);
			} else {

				// Failed (non-success) Response received
				$errorMsg = 'Response error - Error encountered: '
						  . '[' . $metadata['http_code'] . '] '
					      . '(Request: ' . $apiUrl . ') ';
				if ( ! empty($response) ) {
					$data = json_decode($response);
					$errorMsg .= $data->error;
				}

				// Throw exception
				throw new Exception($errorMsg);

			}
		} catch (Exception $e) {
			// Failed to do curl call
			throw new Exception($e->getMessage());
		}

		return $data;

	}
}
// API options
$options = $config['500px'];
// Initialize Cient
$five00Px = Five00PxPubClient::factory($options);
$tags = array(
		'cats'
		// Tag 2
);
// Search Params for the Photo
$apiParams = array (
	'feature' => 'user',
	'username'	=> 'meetrichpearson',
	'image_size'	=> 31,
	'rpp'	=> 10,
);

$posts = array();

// Do the API call
try {
    $response = $five00Px->api('photos', $apiParams);
    if($response->photos){
      foreach ($response->photos as $project) {
        dd($project);
        // Project data
        $post['date'] = strtotime($project->created_at);
        $post['title'] = $project->name;
        $post['image'] = $project->image_url;
        $post['category'] = $project->description;
        $post['url'] = 'https://500px.com/'.$project->url;
        $posts[] = $post;
      }
    }
} catch (Exception $e) {
    print $e->getMessage();
}

return $posts;


?>
