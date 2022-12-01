<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Quill\Quill;
use ParagonIE\Sapient\CryptographyKeys\{
    SigningSecretKey,
    SigningPublicKey
};

foreach(array('asset', 'signature', 'publickey', 'hmac') as $p){
	if(isset($_POST[$p])) continue;
	http_response_code(400);
	die("$p missing");
}

$hmac = hash_hmac('sha256', $_POST['asset'], Jadeblok\Config::getHMACKey());
if(!hash_equals($hmac, $_POST['hmac'])){
	http_response_code(400);
	die("Invalid hmac");
}


$sa = new Jadeblok\SignedAsset($_POST['asset'], $_POST['publickey'], $_POST['signature']);
$previous = Jadeblok\ChronicleHelper::getByAsset($sa->getSha256());
$requiresKey = count($previous) > 0 ? $previous[0]['contents']->getPublickey() : null;
if($requiresKey){
	try{
		//We've got a fairly limited gpg setup without extra libaries, 
		//so validate by swapping out the keys and see if the signature
		//still validates.
		$saTest = new Jadeblok\SignedAsset($_POST['asset'], $requiresKey, $_POST['signature']);
	}catch(Jadeblok\AssetSignatureException $e){
		http_response_code(400);
		header('Content-Type: application/json');
		die(json_encode(array(
			'error' => 'Asset already claimed by different key'
		)));
	}
}

$quill = new Quill(
	Jadeblok\Config::getChronicleHost(),
	Jadeblok\Config::getChronicleClientId(),
	Jadeblok\Config::getChroniclePublickey(),
	Jadeblok\Config::getChroniclePrivateKey(),
	new GuzzleHttp\Client())
;
$assetData = $sa->toJson();
if($assetData){
	$quill->write($assetData);
	
}else{
	throw new RuntimeException("Error encoding asset data");
}
