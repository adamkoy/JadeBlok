<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$results = Jadeblok\ChronicleHelper::getExport();
$results = array_map(
	function($a){
		$a['contents'] = Jadeblok\SignedAsset::fromJson($a['contents']);
		return $a;
	}, $results
);

$s = new Jadeblok\JSmarty();
$s->assign('navSelect', 'chain');
$s->assign('chain', array_reverse($results));
$s->display('viewChain.tpl');