<?php
namespace Jadeblok;

class JSmarty extends \Smarty
{
	function __construct(){
		\Smarty::__construct();
		$this->template_dir = __DIR__ . '/../templates/';
		$this->compile_dir = __DIR__ . '/../templates_c/';
		$this->config_dir = __DIR__ . '/../configs/';
		$this->cache_dir = __DIR__ . '/../cache/';
		$this->assign('navLinks', array(
			'Home' => '/',
			'Create key' => '/createKey.php',
			'Add asset' => '/addAsset.php',
			'Lookup asset' => '/lookupAsset.php',
			'View chain' => '/viewChain.php'
		));
		$this->assign('navSelect', 'home');
	}

}