<?php
namespace Jadeblok;

use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\{
	SigningPublicKey,
	SigningSecretKey
};

class Config
{
	public static function getChroniclePublickey()
	{
		$host = self::getChronicleHost();
		$pubkey = null;
		$pubkeyFile = sys_get_temp_dir() . '/chronicle_pubkey';
		if(getenv('CHRONICLE_SERVERPUBLICKEY') !== false){
			$pubkey = Base64UrlSafe::decode(getenv('CHRONICLE_SERVERPUBLICKEY'));
		}else if(file_exists($pubkeyFile)){
			$pubkey = Base64UrlSafe::decode(file_get_contents($pubkeyFile));
		}else{
			$data = file_get_contents('http://' . $host . '/chronicle');
			if(!$data) throw new \RuntimeException(
				"Error contacting chronicle: $host"
			);
			$json = json_decode($data, true);
			if(!$json) throw new \RuntimeException('Error decoding chronicle JSON');
			$pubkey = $json['public-key'];
			file_put_contents($pubkeyFile, $pubkey);
			$pubkey = Base64UrlSafe::decode($pubkey);
		}
		return new SigningPublicKey($pubkey);
	}

	public static function getChroniclePrivateKey()
	{
		return new SigningSecretKey(
			Base64UrlSafe::decode(
				self::getEnvOrThrow('CHRONICLE_PRIVATEKEY')
			)
		);
	}

	public static function getChronicleClientId()
	{
		return self::getEnvOrThrow('CHRONICLE_CLIENTID');
	}

	public static function getChronicleHost()
	{
		return self::getEnvOrThrow('CHRONICLE_HOST');
	}

	public static function getGnupgHome()
	{
		return __DIR__ . '/../.gnupg';
	}

	public static function getHMACKey()
	{
		return self::getEnvOrThrow('JADEBLOK_HMAC_KEY');
	}

	public static function getEnvOrThrow($env)
	{
		$rs = getenv($env);
		if($rs === false) throw new \RuntimeException(
			'Environment variable missing: '. $env
		);
		return $rs;
	}
}