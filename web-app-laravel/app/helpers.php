<?php
// My common functions
use Carbon\Carbon;

function somethingOrOther()
{
    return (mt_rand(1,2) == 1) ? 'something' : 'other';
}

function getLoraServerToken(){
	if (Cache::has('lora_server_token')) {
		return Cache::get('lora_server_token');
	}

	return renewLoraServerToken();
}

function renewLoraServerToken(){
	$client = new \GuzzleHttp\Client(['verify' => false ]);
	$username = getenv('LORASERVER_USERNAME') ?: 'admin';
	$password = getenv('LORASERVER_PASSWORD') ?: 'admin';
	$url = (getenv('LORASERVER_URL') ?: 'https://localhost'). ':'. (getenv('LORASERVER_PORT') ?: '8080');
	$endpoint = '/api/internal/login';
	$method = 'POST';
	$body = '{
			"username": "' .$username.'",
			"password": "' .$password.'"'.
		'}';

	$res = null;
	try {
		$res = $client->request($method, $url. $endpoint, [
		'body' => $body
		]);
	} catch (Exception $e) {
		return $e;
	}
	if ($res != null && $res->getStatusCode() == 200) {
		$json = json_decode($res->getBody(), true);
		$jwt = $json['jwt'];
		$expiresAt = Carbon::now()->addDays(1)->addHours(-1);
		Cache::put('lora_server_token', $jwt, $expiresAt);
		return $jwt;
	}
	return null;
}

function sendLoraServerApiRequest($method, $endpoint, $body){
	$client = new \GuzzleHttp\Client(['verify' => false ]);
    $url = (getenv('LORASERVER_URL') ?: 'https://localhost'). ':'. (getenv('LORASERVER_PORT') ?: '8080');

    $headers = ['Authorization' => getLoraServerToken()];

    try {
        $res = $client->request($method, $url. $endpoint, [
        'body' => $body,
        'headers' => $headers
        ]);
        // todo: check response code for token expire

        return $res;
    } catch (Exception $e) {
    	// todo:
    }
    return null;
}

// store token in cache for one day expire date.
// if cache is expire request new token
?>
