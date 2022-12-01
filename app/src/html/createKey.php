<?php

require_once(__DIR__ . '/../vendor/autoload.php');

#use Jadeblok\JSmarty;

$s = new Jadeblok\JSmarty();
$s->assign('navSelect', 'Create key');
$s->display('createKey.tpl');

?>