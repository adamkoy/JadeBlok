<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

$file = Jadeblok\HTTPHelper::getFileDetails('assetFile');
$fileHash = hash_file("sha256", $file['tmp_name']);

foreach(array('firstname', 'lastname') as $f){
	if(!isset($_POST[$f])){
		throw new RuntimeException ("$f missing from asset details");
	}
}

$asset = new Jadeblok\Asset($fileHash, $_POST['firstname'], $_POST['lastname'],
	isset($_POST['description']) ? $_POST['description'] : null,
	isset($_POST['rating']) ? $_POST['rating'] : null
);
$json = $asset->toJson();
$hmac = hash_hmac('sha256', $json, Jadeblok\Config::getHMACKey());
$previous = Jadeblok\ChronicleHelper::getByAsset($fileHash);

header('Content-Type: application/json');
die(json_encode(array(
	'asset' => $json,
	'hmac' => $hmac,
	'requiresKey' => count($previous) > 0 ? $previous[0]['contents']->getPublickey() : null
)));

