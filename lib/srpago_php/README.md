# SrPago PHP bindings

You can sign up for a SrPago account at https://www.srpago.com.

## Requirements

PHP 5.3.3 and later.

## Composer - Installation  

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require srpago/ecommerce/srpago_php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```


## Manual - Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/srpago-dev/srpago-php). Then, to use the bindings, include the `init.php` file.

```php
//Required
include_once '/vendor/srpago/ecommerce/srpago_php/init.php';
```

## Dependencies

The bindings require the following extension in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`json`](https://secure.php.net/manual/en/book.json.php)
- [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Simple usage looks like:

```php
/// Librerías requeridas
  include_once 'vendor/ecommerce/srpago_php/init.php';



  /// Configuración de ambiente
  \SrPago\SrPago::setLiveMode(false);
  \SrPago\SrPago::setApiKey('KEY');
  \SrPago\SrPago::setApiSecret('SECRET');


  /// Parámetros de cobro
  $chargeParams = array(
       "amount"=>10.0,
       "description" => "demo de cargo directo con token tok_5ac666440e35f",
       "reference"=> "AB98XXCVBD",
       "ip"=> "189.203.45.58",//OPTIONAL REMOTE IP
       "latitude"=> "-9.11111111",//OPTIONAL REMOTE IP
       "longitude"=> "9.999999",//OPTIONAL REMOTE IP
       //"installments"=>3,    //OPTIONAL  Montly installments 3,6,9,12
       "source"=>"tok_dev_5b32c39085517" //TOKEN CARD REQUIRED
     );

     ///Agrega información de metadata
     /// see more information in “Metadata Object” section
     $metadata = array(
       "items"=>array(
          "item" => array(
              array(
                "itemNumber"=> "193487654",
                "itemDescription"=> "iPhone 6 32gb",
                "itemPrice"=> "599.00",
                "itemQuantity"=> "1",
                "itemMeasurementUnit"=> "Pza",
                "itemBrandName"=> "Apple",
                "itemCategory"=> "Electronics",
                "itemTax"=> "12.95"
            ),
          )
        )
     );
     $chargeParams['metadata'] = $metadata;


try {
     /// Procesar cobro
     $chargesService = new \SrPago\Charges();
     $charge = $chargesService->create($chargeParams);

}catch(\SrPago\Error\SrPagoError $ex){
    
}

```

## Documentation

Please see https://srpago.com/docs/api for up-to-date documentation.

## Legacy Version Support

If you are using PHP 5.2, you can download v1.18.0 ([zip](https://github.com/srpago/srpago-php/archive/v1.18.0.zip), [tar.gz](https://github.com/srpago/srpago-php/archive/v1.18.0.tar.gz)) from our [releases page](https://github.com/srpago/srpago-php/releases). This version will continue to work with new versions of the SrPago API for all common uses.


## Development

Install dependencies:

``` bash
composer install
```

### SSL / TLS configuration option

See the "SSL / TLS compatibility issues" paragraph above for full context. If you want to ensure that your plugin can be used on all systems, you should add a configuration option to let your users choose between different values for `CURLOPT_SSLVERSION`: none (default), `CURL_SSLVERSION_TLSv1` and `CURL_SSLVERSION_TLSv1_2`.
