<?php

$srpagoFunctions = array(
  'json_decode'=>'SrPago requires JSON extension',
  'curl_init'=>'SrPago requires CURL extension',
  'mb_detect_encoding'=>'SrPago requires MB (Multibyte String) extension',
  'openssl_public_encrypt'=>'SrPago requires openssl extension',
);

if(version_compare(PHP_VERSION, '5.3.3', '<')) {
  throw new Exception('SrPago should be run on PHP >= 5.3.3');
}

foreach($srpagoFunctions as $method=>$message){
  if (!function_exists($method)) {
      throw new Exception($message);
    }
}
if (!version_compare(PHP_VERSION, '7.1.0', '>=')) {
  if (!function_exists('mcrypt_encrypt')) {
    throw new Exception('SrPago requires mcrypt extension');
  }
}


// SrPago singleton
require(dirname(__FILE__) . '/lib/SrPago.php');

// Errors
require(dirname(__FILE__) . '/lib/Error/SrPagoError.php');

//HttpClient
require(dirname(__FILE__) . '/lib/Http/HttpClient.php');

require(dirname(__FILE__) . '/lib/Util/Encryption.php');

// Resources
require(dirname(__FILE__) . '/lib/Base.php');

// SrPago API Resources
require(dirname(__FILE__) . '/lib/Token.php');
require(dirname(__FILE__) . '/lib/Operations.php');
require(dirname(__FILE__) . '/lib/Charges.php');
require(dirname(__FILE__) . '/lib/Customer.php');
require(dirname(__FILE__) . '/lib/CustomerCards.php');
