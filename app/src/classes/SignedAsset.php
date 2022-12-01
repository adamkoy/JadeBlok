<?php
namespace Jadeblok;

class SignedAsset
{
	public function __construct($asset, $publickey, $signature)
	{
		$this->assetJson = $asset;
		$this->asset = Asset::fromJson($asset);
		$this->signature = $signature;
		$this->publickey = $publickey;
		$this->publickeyFingerprint = null;

		$this->validateSignature();
	}

	public function validateSignature(){
		$gpg = new TempGnupg();
		$gpg->seterrormode(GNUPG_ERROR_EXCEPTION);

		try{
			$importInfo = $gpg->import($this->publickey);
			$verifyInfo = $gpg->verify($this->assetJson, $this->signature);
			if(!$verifyInfo) throw new AssetSignatureException("Asset signature did not validate");
			if(count($verifyInfo) !== 1){
				error_log("Unexpected signature count: " . var_export($verifyInfo, true));
				throw new AssetSignatureException('Unexpected signature count');
			}
			$verifyInfo = $verifyInfo[0];
			if(strcmp($verifyInfo['fingerprint'], $importInfo['fingerprint']) !== 0){
				error_log('Signature not signed by correct key: '
				. $verifyInfo['fingerprint'] . ' vs '
				. $importInfo['fingerprint']);
				throw new AssetSignatureException('Signature not signed by correct key');
			}

			//error_log("IMPORT INFO: " . var_export($importInfo, true));
			//error_log("VERIFY INFO: " . var_export($verifyInfo, true));
			//error_log("KEY INFO: " . var_export($gpg->keyinfo($importInfo['fingerprint']), true));
			$this->publickeyFingerprint = $verifyInfo['fingerprint'];	
		}catch(GPGException $e){
			throw new AssetSignatureException("Signature validation failed", 0, $e);
		}
	}

	public function getDate()
	{
		return $this->asset->getDate();
	}

	public function getSha256()
	{
		return $this->asset->getSha256();
	}

	public function getUploaderLastname()
	{
		return $this->asset->getUploaderLastname();
	}

	public function getUploaderFirstname()
	{
		return $this->asset->getUploaderFirstname();
	}

	public function getDescription()
	{
		return $this->asset->getDescription();
	}

	public function getRating()
	{
		return $this->asset->getRating();
	}

	public function getPublickey()
	{
		return $this->publickey;
	}

	public function toJson()
	{
		$rs = json_encode(array(
			'asset' => $this->assetJson,
			'signature' => $this->signature,
			'publickey' => $this->publickey,
		));
		if(!$rs) throw new \RuntimeException("Error encoding SignedAsset");
		return $rs;
	}

	public static function fromJson($json)
	{
		$data = json_decode($json, true);
		if(!$data) throw new \RuntimeException("Error decoding JSON");
		foreach(array('asset', 'signature', 'publickey') as $p){
			if(isset($data[$p])) continue;
			throw new \RuntimeException("JSON error $p missing");
		}
		return new SignedAsset($data['asset'], $data['publickey'], $data['signature']);
	}

}