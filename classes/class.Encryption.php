<?php

/**
 * Encryption / decryption class, using a secret key
 * Inspired by http://www.99points.info/2010/06/php-encrypt-decrypt-functions-to-encrypt-url-data/
 */

class Encryption {
	
	public static function encode($value){
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, Config::ENCRYPTION_KEY, $value, MCRYPT_MODE_ECB, $iv);
		return base64_encode($crypttext);
	}
	
	public static function decode($value){
		$value = base64_decode($value);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, Config::ENCRYPTION_KEY, $value, MCRYPT_MODE_ECB, $iv);
	}
	
}
