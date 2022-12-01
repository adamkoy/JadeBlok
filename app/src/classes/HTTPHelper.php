<?php
namespace Jadeblok;


class HTTPHelper
{
	public static function hasUploadedFile($name)
	{
		if(!isset($_FILES[$name])) return false;
		if($_FILES[$name]['error'] == UPLOAD_ERR_NO_FILE) return false;
		
		return true;
	}

	public static function getFileDetails($name)
	{
		if(!self::hasUploadedFile($name)){
			http_response_code(400);
			header('Content-Type: application/json');
			die(json_encode(array("error" => "No file given for $name")));
		}
		$file = $_FILES[$name];
		switch($file['error']){
			case UPLOAD_ERR_OK: break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				http_response_code(400);
				header('Content-Type: application/json');
				die(json_encode(array("error" => "File too large")));
			default:
				http_response_code(400);
				header('Content-Type: application/json');
				die(json_encode(array("error" => "File upload error")));
		}

		if(!is_uploaded_file($file['tmp_name'])){
			throw new RuntimeException("Not uploaded file");
		}

		return $file;
	}
	
}