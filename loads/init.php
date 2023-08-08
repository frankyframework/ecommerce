<?php
use Franky\Core\ObserverManager;
$ObserverManager = new ObserverManager;

include 'util.php';
include 'paypal.php';

__bindtextdomain("ecommerce",'ecommerce');


if (function_exists('bind_textdomain_codeset'))
{
    bind_textdomain_codeset("ecommerce", 'UTF-8');
}

if(getCoreConfig('ecommerce/conekta/enabled') == 1)
{

  \Conekta\Conekta::setApiKey((getCoreConfig('ecommerce/conekta/sandbox') == 1 ? getCoreConfig('ecommerce/conekta/keysandbox') :  getCoreConfig('ecommerce/conekta/key')));
  \Conekta\Conekta::setApiVersion("2.0.0");
  include 'conekta.php';
  $ObserverManager->addObserver('register_new_user','checkCustomerConekta');
  $ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'checkCustomerConekta');
  $ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'updateCustomerConekta');

}
if(getCoreConfig('ecommerce/openpay/enabled') == 1)
{
  Openpay::setId((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/idsandbox') :  getCoreConfig('ecommerce/openpay/id')));
  Openpay::setApiKey((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/secretsandbox') :  getCoreConfig('ecommerce/openpay/secret')));
  Openpay::setProductionMode((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? false : true));

  include 'openpay.php';
  $ObserverManager->addObserver('register_new_user','checkCustomerOpenpay');
  $ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'checkCustomerOpenpay');
  $ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'updateCustomerOpenpay');
}
if(getCoreConfig('ecommerce/sr-pago/enabled') == 1)
{

  include 'srpago.php';
  $ObserverManager->addObserver('register_new_user','checkCustomerSrpago');
  $ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'checkCustomerSrpago');
  $ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'updateCustomerSrpago');
}
$ObserverManager->addObserver('login_user_'.getCoreConfig("base/user/default-role"),'setCarritoUser');
$ObserverManager->addObserver('register_new_user','setCarritoUser');

define("OBJETO_PRODUCTOS", getCoreConfig('ecommerce/product/object')); // \Catalog\model\CatalogproductsModel
define("DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE", getCoreConfig('ecommerce/product/path_images')); // catalog/products/
define("DETALLE_PRODUCTOS_ECOMMERCE", getCoreConfig('ecommerce/product/url-detalle'));



$MyMetatag->setJs("/modulos/ecommerce/web/js/ecommerce.js");
$MyMetatag->setJs("/modulos/ecommerce/web/js/ajax.ecommerce.js");
$MyMetatag->setCss("/modulos/ecommerce/web/css/cart.css");
?>
