<?php
//Security Level: AES 256
//Encrypt the string
//$encryptedString = encode("Encrypted");
//Decrypt the string
//$decryptedString = decode($string);

// The key to encrypting and decrypting your string
define('SALT', 'SuPeRsEcReT');

function encode($text, $salt = SALT){ 
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
}

function decode($text, $salt = SALT){
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
}