<?php
namespace Jadeblok;

use GuzzleHttp\Client;
use ParagonIE\Sapient\{
	Adapter\Guzzle,
	Sapient

};

class ChronicleHelper
{
	public static function getExport()
	{
		$sapient = new Sapient(new Guzzle(new Client()));
		$url = Config::getChronicleHost() . '/chronicle/export';
		$http = new Client();
		$response = $http->get($url);
		$decoded = $sapient->decodeSignedJsonResponse(
			$response,
			Config::getChroniclePublickey()
		);
		// If the status was anything except "OK", raise the alarm:
		if($decoded['status'] !== 'OK'){
			if (\is_string($decoded['message'])) {
				throw new RuntimeException($decoded['message']);
			}
			throw new RuntimeException('An unknown error has occurred with Chronicle');
		}

		$results = array();
		if (is_array($decoded['results'])) $results = $decoded['results'];

		return $results;
	}

	public static function getByAsset($hash)
	{
		$data = self::getExport();

		//For speed we can do a string search for the hash first
		$data = array_filter($data,
			function($a) use($hash){
				return strpos($a['contents'], $hash) !== false;
			}
		);
		$data = array_map(
			function($a){
				$a['contents'] = SignedAsset::fromJson($a['contents']);
				return $a;
			}, $data
		);
		$data = array_filter($data,
			function($a) use($hash){
				return strcmp($a['contents']->getSha256(), $hash) == 0;
			}
		);
		//Rebuild indexes
		$data = array_values($data);
		return $data;
	}
	
}