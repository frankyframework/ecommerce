<?php
use \Ecommerce\model\producto_pedidoModel;
use \Ecommerce\model\carrito;
use \Ecommerce\model\pedidos as pedidos_model;
use \Ecommerce\entity\pedidos;
use \Ecommerce\entity\producto_pedido;
use Ecommerce\entity\direcciones_facturacion as DireccionesFacturacionEntity;
use Ecommerce\model\direcciones_facturacion;
use Ecommerce\entity\direcciones as DireccionesEnvioEntity;
use Ecommerce\model\direcciones;
use Franky\Core\ObserverManager;

if($MySession->GetVar('pay_free') == "")
{
    $MyRequest->redirect();
}


$MySession->UnsetVar('pay_free');

$productos =  OBJETO_PRODUCTOS;
$MyProducto =  new $productos();

$MyCarritoCompras =  new carrito();
$MyPedido = new pedidos_model();
$MyPedidoEntity = new pedidos();
$MyPedidoProducto = new producto_pedidoModel();
$MyPedidoProductoEntity = new producto_pedido();

$productos_comprados = getCarrito();
$items = array();


if(empty($productos_comprados)){
         $MyRequest->redirect();
}
$total = 0;
foreach($productos_comprados['productos'] as $producto)
{
    $total  += $producto["precio"];
}
if($total > 0)
{
    $MyRequest->redirect($MyRequest->url(CHECKOUT_ECOMMERCE));
}


$referencia = "";
$status_pago = normalizeStatusTransaccion('paid');
$data = $MySession->GetVar('checkout');

if(isset($data["id_facturacion"]))
{
    if(is_numeric($data["id_facturacion"]))
    {
        $id_direccion_facturacion = $data["direccion_facturacion"];
    }
}
else {
    if(isset($data["direccion_facturacion"]) && !empty($data["direccion_facturacion"]))
    {
        $direcciones_facturacion = new direcciones_facturacion();
        $DireccionesFacturacionEntity = new DireccionesFacturacionEntity($data["direccion_facturacion"]);
        $DireccionesFacturacionEntity->setUid($MySession->GetVar('id'));
        $DireccionesFacturacionEntity->setFecha(date('Y-m-d H:i:s'));
        $DireccionesFacturacionEntity->setStatus(1);
        $direcciones_facturacion->save($DireccionesFacturacionEntity->getArrayCopy());
        $id_direccion_facturacion = $data["direccion_facturacion"];

    }
}

if(isset($data["id_envio"]))
{
    $direccion_envio = $data["direccion_envio"];

}
else {
    if(isset($data["direccion_envio"]))
    {
        $direcciones_envio = new direcciones();
        $DireccionesEnvioEntity= new DireccionesEnvioEntity($data["direccion_envio"]);
        $DireccionesEnvioEntity->setUid($MySession->GetVar('id'));
        $DireccionesEnvioEntity->setFecha(date('Y-m-d H:i:s'));
        $DireccionesEnvioEntity->setStatus(1);
        $direcciones_envio->save($DireccionesEnvioEntity->getArrayCopy());
        $direccion_envio = $data["direccion_envio"];
    }
}

if(isset($data["pickup"]))
{
    $direccion_envio = $data["direccion_pickup"];
}



$MySession->SetVar('checkout',array());
$MyPedidoEntity->setId_direccion_envio(json_encode($direccion_envio));
$MyPedidoEntity->setId_direccion_facturacion(json_encode($id_direccion_facturacion));
$MyPedidoEntity->setFecha(date('Y-m-d H:i:s'));
$MyPedidoEntity->setUid($MySession->GetVar('id'));
$MyPedidoEntity->setStatus($status_pago);
$MyPedidoEntity->setMetodo_pago("pay_free");
$MyPedidoEntity->setMetodo_envio(0);
$MyPedidoEntity->setMonto_compra($productos_comprados['gran_total']);
$MyPedidoEntity->setSubtotal($productos_comprados['subtotal']);
$MyPedidoEntity->setIva($productos_comprados['iva_total']);
$MyPedidoEntity->setMonto_pagado(0);
$MyPedidoEntity->setMonto_envio(0);
$MyPedidoEntity->setReferencia($referencia);

if($MyPedido->save($MyPedidoEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
    $pedido = $MyPedido->getUltimoID();

    foreach($productos_comprados['productos'] as $producto)
    {
        $MyPedidoProductoEntity->setCaracteristicas(json_encode($producto["caracteristicas"]));
        $MyPedidoProductoEntity->setId_pedido($pedido);
        $MyPedidoProductoEntity->setId_producto($producto["id"]);
        $MyPedidoProductoEntity->setQty($producto["qty"]);
        $MyPedidoProductoEntity->setPrecio($producto["precio"]);

        $MyPedidoProducto->save($MyPedidoProductoEntity->getArrayCopy());
    }
    $productos_html = render(PROJECT_DIR.'/modulos/ecommerce/diseno/email/productos.phtml',['items' =>$productos_comprados['productos']]);


    $campos = array("orden" => $pedido,"nombre" =>$MySession->GetVar('nombre'),"email" =>$MySession->GetVar('email'),'productos' =>$productos_html,'subtotal' => getFormatoPrecio($productos_comprados['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
    'iva' => getFormatoPrecio($productos_comprados['iva_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
    'gran_total' => getFormatoPrecio($productos_comprados['gran_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),'metodo_pago' =>'No requerido','status' => getStatusTransaccion($status_pago),'referencia' => $referencia);


    $TemplateemailModel    = new \Base\model\TemplateemailModel;
    $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
    $TemplateemailEntity->id(getCoreConfig('ecommerce/ventas/email-order-free'));
    $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
    $registro  = $TemplateemailModel->getRows();

    sendEmail($campos,$registro);

    $ObserverManager = new ObserverManager;

    $ObserverManager->dispatch('finalizar_orden_ecommerce',[$pedido]);
}
else {
    // hubo un error al guardar.
}
$MyCarritoCompras->delete(getMyIdCarrito());


$MyMetatag->setTitulo(_ecommerce("Confirmacion de pedido"));
$MyMetatag->setDescripcion(_ecommerce("Confirmacion de pedido"));
$MyMetatag->setkeywords("");

?>
