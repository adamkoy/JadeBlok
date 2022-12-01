<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

$fileHash = null;
if(isset($_POST['sha256']) && strlen($_POST['sha256']) > 6){
	$fileHash = $_POST['sha256'];
}elseif(Jadeblok\HTTPHelper::hasUploadedFile('assetFile')){
	$file = Jadeblok\HTTPHelper::getFileDetails('assetFile');
	$fileHash = hash_file("sha256", $file['tmp_name']);
	if(!$fileHash) throw new RuntimeException("Error getting file hash: " . $file['tmp_name']);
}else{
	http_response_code(400);
	header('Content-Type: application/json');
	die(json_encode(array("error" => "File or hash not given")));
}

$chain = Jadeblok\ChronicleHelper::getByAsset($fileHash);

if(isset($_POST['format']) && $_POST['format'] == "html"){
	$s = new Jadeblok\JSmarty();
	$s->assign('navSelect', 'chain');
	$s->assign('chain', array_reverse($chain));
	$s->display('viewChain.tpl');
	exit();
}else{
	$chain = array_map(
		function($a){
			$a['contents'] = $a['contents']->toJson();
			return $a;
		}, $chain
	);
	header('Content-Type: application/json');
	die(json_encode($chain));
}