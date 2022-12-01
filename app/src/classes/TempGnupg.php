<?php
namespace Jadeblok;

use \gnupg;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;

class TempGnupg
{
	public function __construct()
	{
		$this->path = $this->createTempDir();
		putenv('GNUPGHOME='.$this->path);

		//We need to do this because if we extend gnupg it can sometimes
		//initialise the home directory before we finish setting it up
		$this->gnupg = new gnupg();
		$this->gnupg->seterrormode(gnupg::ERROR_EXCEPTION);
	}

	public function addsignkey(...$args){ return $this->gnupg->addsignkey(...$args); }
	public function clearsignkeys(...$args){ return $this->gnupg->clearsignkeys(...$args); }
	public function geterror(...$args){ return $this->gnupg->geterror(...$args); }
	public function import(...$args)
	{
		if(strpos(trim($args[0]), '-----BEGIN PGP') !== 0)
			throw new \InvalidArgumentException(
				"Key missing or did not begin with PGP block"
		);
		$rs = $this->gnupg->import(...$args);
		if(!$rs || !$rs['imported']){
			throw new \RuntimeException("Error importing key");
		}
		return $rs;
	}
	public function keyinfo(...$args){ return $this->gnupg->keyinfo(...$args); }
	public function setarmor(...$args){ return $this->gnupg->setarmor(...$args); }
	public function seterrormode(...$args){ return $this->gnupg->seterrormode(...$args); }
	public function setsignmode(...$args){ return $this->gnupg->setsignmode(...$args); }
	public function sign(...$args){ return $this->gnupg->sign(...$args); }
	public function verify(...$args){
		$rs = $this->gnupg->verify(...$args);
		if(!$rs) throw new GPGException("Error verifying signature");
		foreach($rs as $r){
			//Although there's no constant for it if neither VALID,
			//GREEN, or RED flags are set there's implicit 'yellow'
			//which means signer unknown but signature may be valid
			//see other flags. So it's up to the application to
			//figure it out.
			if(!($r['summary'] & gnupg::SIGSUM_VALID) && $r['summary'] !== 0)
				throw new GPGException("Summary reported invalid: " . var_export($r, true));
		}
		return $rs;
	}

	private function createTempDir()
	{
		$dir = sys_get_temp_dir();
		if(!is_dir($dir) || !is_writable($dir))
			throw new \RuntimeException("sys temp dir permission error");
		for($i=0; $i<10; $i++){
			$path = sprintf('%s%s%s%s', $dir, DIRECTORY_SEPARATOR, 'tempgnupg_', uniqid());
			if(mkdir($path, 0700)) return $path;
		}
		throw new \RuntimeException("Ran out of retries making tempdir");
	}

	public function __destruct()
	{
		$it = new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file){
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($this->path);
	}
}