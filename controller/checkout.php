<?php
use Ecommerce\Form\checkoutForm;
use Ecommerce\Form\direccionesForm;
use Ecommerce\Form\CustomerForm;

$productos =  OBJETO_PRODUCTOS;
$MyProducto =  new $productos();

$productos_comprados = getInfoCarrito();
if(empty($productos_comprados['productos']))
{
    $MyRequest->redirect($MyRequest->url(CARRITO_COMPRAS));
}


$direcciones_facturacion = makeHTMLDireccion("facturacion",$MySession->GetVar("id"));
$direcciones_facturacion["no_requiere"] = "No requiere factura";
$direcciones_facturacion["otra"] = "Nueva direccion";
$DireccionCheckoutForm = new checkoutForm("frm_direccion");
$DireccionCheckoutForm->addDirecionFacturacion($direcciones_facturacion);
$DireccionCheckoutForm->setAtributoInput("id_facturacion", "value", "no_requiere");
$DireccionCheckoutForm->addSubmit();


if ($MySession->LoggedIn()) {
    $direcciones_envio = makeHTMLDireccion("envio",$MySession->GetVar("id"));
  if(!empty($direcciones_envio)) {
    $direcciones_envio["otra"] = 'Nueva dirección';
    $DireccionEnvioCheckoutForm = new checkoutForm("frm_direccion_envio");
    $DireccionEnvioCheckoutForm->addDirecionEnvio($direcciones_envio);
    $DireccionEnvioCheckoutForm->addSubmit();

  }
   
  
}


$direccionesForm = new direccionesForm("frmdirecciones");
$direccionesForm->addEntrecalles();
$direccionesForm->addCheck("save_address","Guardar direccion");

$direccionesForm->addSubmit();
$direccionesForm->setAtributoInput("guardar","value","Siguiente");


$direccionesFacturacionForm = new direccionesForm("frmdirecciones_facturacion");
$direccionesFacturacionForm->addName();
$direccionesFacturacionForm->addRFC();
$direccionesFacturacionForm->addSubmit();
$direccionesFacturacionForm->setAtributoInput("guardar","value","Siguiente");
$direccionesFacturacionForm->addCheck("save_address","Guardar direccion");
$customerForm = new CustomerForm("frmcustomer");
?>
