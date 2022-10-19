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
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();

if($MySession->GetVar('establecimiento_pay') == "" ||  $MyRequest->getRequest('establecimiento_pay') == "")
{
    $MyRequest->redirect();
}

if($MySession->GetVar('establecimiento_pay') != $MyRequest->getRequest('establecimiento_pay'))
{
    $MyRequest->redirect();
}

$cupon = $MySession->GetVar('cupon_checkout');
if($cupon != false)
{
    $valida_cupo = validaCuponEcommerce($cupon['cupon']);
    if($valida_cupo['error'] == true){
        ecommerce_removeCupon();
    }
}


$ObserverManager = new ObserverManager;
$ObserverManager->dispatch('prepara_orden_ecommerce',[]);
$MySession->UnsetVar('establecimiento_pay');

$productos =  OBJETO_PRODUCTOS;
$MyProducto =  new $productos();

$MyCarritoCompras =  new carrito();
$MyPedido = new pedidos_model();
$MyPedidoEntity = new pedidos();
$MyPedidoProducto = new producto_pedidoModel();
$MyPedidoProductoEntity = new producto_pedido();

$data = $MySession->GetVar('checkout');
$productos_comprados = getCarrito();
$items = array();


if(empty($productos_comprados)){
         $MyRequest->redirect();
}

foreach($productos_comprados['productos'] as $producto)
{
    $items[] = array("name" => $producto["nombre"],"unit_price" => $producto["precio"],"quantity" => $producto["qty"]);
}
if($data['monto_envio'] > 0)
{
    $items[] = array("name" => "Servicio de envio","unit_price" => $data["monto_envio"],"quantity" => 1); 
}


/*Aqui srpago*/




try{
    $order_id = md5(time().$MySession->GetVar('id'));
    

      
      $chargeParams = array(
        "payment" => array(
          "reference" =>
            array(
                "description" => $order_id
            )
        ),
        "total" => $productos_comprados['gran_total'] + $data['monto_envio'] - (isset($productos_comprados['descuento']) ? $productos_comprados['descuento'] : 0),
        "store" => "oxxo"
        );

      $metadata = array(
          "member" => [
              "memberFullName" => $MySession->GetVar('nombre'),
              "memberEmailAddress" => $MySession->GetVar('email'),
          ]
          );
     
      $order = pagoOXXO($chargeParams,$metadata);
   
     //print_r($order);
        if($order == false)
        {
            $MyFlashMessage->setMsg("error","Error al procesar la orden");
            $MyRequest->redirect($MyRequest->getReferer());
        }

  } catch (\Exception $e) {
      $MyFlashMessage->setMsg("error",$e->getMessage());
      $MyRequest->redirect($MyRequest->getReferer());
     
      
  }


  $referencia = 
          [
          'id' => $order['result']['transaction'],
          'status' => $order['result']['status_code'],
          'url' => $order['result']['url'],
          'barcode' => $order['result']['barcode'],
          'bank_account_number' => $order['result']['bank_account_number'],
          'barcode_url' => $order['result']['barcode_url'],
          'expiration_date' => $order['result']['expiration_date'],
          'order_id' => $order_id,
          ];

       
$status_pago = normalizeStatusTransaccion($order['result']['status_code']);


//print_r($data);

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
        $id_direccion_facturacion =  $data["direccion_facturacion"];

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
$MySession->SetVar('cupon_checkout',array());
$MyPedidoEntity->setId_direccion_envio(json_encode($direccion_envio));
$MyPedidoEntity->setId_direccion_facturacion(json_encode($id_direccion_facturacion));
$MyPedidoEntity->setFecha(date('Y-m-d H:i:s'));
$MyPedidoEntity->setUid($MySession->GetVar('id'));
$MyPedidoEntity->setStatus($status_pago);
$MyPedidoEntity->setMetodo_pago("srpago_oxxo");
$MyPedidoEntity->setMetodo_envio($data['id_metodo_envio']);
$MyPedidoEntity->setMonto_compra($productos_comprados['gran_total']);
$MyPedidoEntity->setSubtotal($productos_comprados['subtotal']);
$MyPedidoEntity->setIva($productos_comprados['iva_total']);
$MyPedidoEntity->setMonto_pagado(0);
$MyPedidoEntity->setMonto_envio($data['monto_envio']);
$MyPedidoEntity->setDescuento($productos_comprados['descuento']);
$MyPedidoEntity->setCupon($cupon['id']);
$MyPedidoEntity->setData_cupon(json_encode($cupon));
$MyPedidoEntity->setReferencia(json_encode($referencia));

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
    $envio_html = render(PROJECT_DIR.'/modulos/ecommerce/diseno/email/metodoenvio.phtml',['direccion' =>$direccion_envio,'metodo_envio' => makeHTMLMetodosEnvio($data['id_metodo_envio'],0)]);

    

    $campos = array(
        "orden" => $pedido,
        "nombre" =>$MySession->GetVar('nombre'),
        "email" =>$MySession->GetVar('email'),
        'productos' =>$productos_html,
        'subtotal' => getFormatoPrecio($productos_comprados['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
        'iva' => getFormatoPrecio($productos_comprados['iva_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
        'envio' => getFormatoPrecio($data['monto_envio'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
        'metodo_envio' =>$envio_html,
        'descuento' => getFormatoPrecio($productos_comprados['descuento'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
        'gran_total' => getFormatoPrecio($productos_comprados['gran_total']+$data['monto_envio']-$productos_comprados['descuento'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),'metodo_pago' =>'Pago en Establecimiento','status' => getStatusTransaccion($status_pago),'referencia' => $referencia);

        $campos['ticket_oxxo'] = $referencia['url'];

   
        $TemplateemailModel    = new \Base\model\TemplateemailModel;
        $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
        $TemplateemailEntity->id(getCoreConfig('ecommerce/sr-pago/email-order-oxxo'));
        $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
        $registro  = $TemplateemailModel->getRows();
    
    sendEmail($campos,$registro);

    $ObserverManager->dispatch('finalizar_orden_ecommerce',[$pedido]);
}
else {
    // hubo un error al guardar.
}
$MyCarritoCompras->delete(getMyIdCarrito());


$MyMetatag->setTitulo(_ecommerce("Confirmacion de pedido establecimiento"));
$MyMetatag->setDescripcion(_ecommerce("Confirmacion de pedido establecimiento"));
$MyMetatag->setkeywords("");

?>
