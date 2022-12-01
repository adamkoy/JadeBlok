<?php
namespace Jadeblok;

use \DateTime;

class Asset
{
	public function __construct($filehash, $firstname, $surname, $description = null, $rating = null, DateTime $created = null)
	{
		if(!is_string($filehash) || strlen($filehash) < 2)
			throw new \RuntimeException("Filehash required for asset");
		if(!is_string($firstname) || strlen($firstname) < 2)
			throw new \RuntimeException("Firstname required for asset");
		if(!is_string($surname) || strlen($surname) < 2)
			throw new \RuntimeException("Surname required for asset");
		if($created === null) $created = new DateTime();

		$this->filehash = $filehash;
		$this->firstname = $firstname;
		$this->surname = $surname;
		$this->description = $description;
		$this->created = $created;
		$this->rating = $rating;
		$this->json = null;
	}

	public function getDate()
	{
		return $this->created;
	}

	public function getSha256()
	{
		return $this->filehash;
	}

	public function getUploaderLastname()
	{
		return $this->surname;
	}

	public function getUploaderFirstname()
	{
		return $this->firstname;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function toJson()
	{
		if($this->json != null) return $this->json;

		$json = json_encode(array(
			'version' => '1.0',
			'asset' => array(
				'sha256' => $this->filehash
			),
			'date' => $this->created->format(DateTime::RFC2822),
			'rating' => $this->rating,
			'uploader' => array(
				'firstname' => $this->firstname,
				'lastname' => $this->surname
			),
			'description' => $this->description
		));
		if($json === false)
			throw new RuntimeException("Error generating asset json");

		return $json;
	}

	private function setJson($json)
	{
		$this->json = $json;
	}

	public static function fromJson($json)
	{
		$data = json_decode($json, true);
		if(!$data) throw new \RuntimeException("Error decoding JSON");
		foreach(array('asset', 'date', 'uploader') as $p){
			if(isset($data[$p])) continue;
			throw new \RuntimeException("JSON error $p missing");
		}
		foreach(array('firstname', 'lastname') as $p){
			if(isset($data['uploader'][$p])) continue;
			throw new \RuntimeException("JSON error uploader/$p missing");
		}
		foreach(array('sha256') as $p){
			if(isset($data['asset'][$p])) continue;
			throw new \RuntimeException("JSON error asset/$p missing");
		}

		$asset = new Asset($data['asset']['sha256'],
			$data['uploader']['firstname'],
			$data['uploader']['lastname'],
			isset($data['description']) ? $data['description'] : null,
			isset($data['rating']) ? $data['rating'] : null,
			DateTime::createFromFormat(DateTime::RFC2822, $data['date'])
		);
		$asset->setJson($json);
		return $asset;
	}
}