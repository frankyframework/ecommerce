<?php
use Franky\Core\ObserverManager;
$ObserverManager = new ObserverManager;

include 'util.php';

__bindtextdomain("ecommerce",'ecommerce');


if (function_exists('bind_textdomain_codeset'))
{
    bind_textdomain_codeset("ecommerce", 'UTF-8');
}

$ObserverManager->addObserver('login_user','setCarritoUser');
$ObserverManager->addObserver('register_new_user','setCarritoUser');
$ObserverManager->addObserver('change_quote','getUpdateCarrito');
$ObserverManager->addObserver('product_order_save','catalogRestaStock');

define("OBJETO_PRODUCTOS", getCoreConfig('ecommerce/product/object')); // \Catalog\model\CatalogproductsModel
define("DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE", getCoreConfig('ecommerce/product/path_images')); // catalog/products/
define("DETALLE_PRODUCTOS_ECOMMERCE", getCoreConfig('ecommerce/product/url-detalle'));



$MyMetatag->setJs("/modulos/ecommerce/web/js/ecommerce.js");
$MyMetatag->setJs("/modulos/ecommerce/web/js/ajax.ecommerce.js");
$MyMetatag->setCss("/modulos/ecommerce/web/css/cart.css");
?>