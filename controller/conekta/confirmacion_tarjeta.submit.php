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
use Franky\Core\validaciones;
use Ecommerce\model\CardsModel;
use Ecommerce\entity\CardsEntity;
use Franky\Core\ObserverManager;

$ObserverManager    = new ObserverManager;
$CardsModel        = new CardsModel();
$CardsEntity       = new CardsEntity();
$data = $MySession->GetVar('checkout');
$ObserverManager->dispatch('prepara_orden_ecommerce',[]);
$cupon = $MySession->GetVar('cupon_checkout');
if($cupon != false)
{
    $valida_cupo = validaCuponEcommerce($cupon['cupon']);
    if($valida_cupo['error'] == true){
        ecommerce_removeCupon();
    }
}

$id_tarjeta = $MyRequest->getRequest('id_tarjeta');
$error = false;
if($MySession->GetVar('tarjeta_conekta') == "" )
{
  $error = true;
}
$MySession->UnsetVar('tarjeta_conekta');

if(empty($id_tarjeta))
{



  $validaciones =  new validaciones();
  $valid = $validaciones->validRules($CardsEntity->setValidation());
  if(!$valid)
  {
      $MyFlashMessage->setMsg("error",$validaciones->getMsg());
      $error = true;
  }


  if(!$MyAccessList->MeDasChancePasar(ADMINISTRAR_TARJETAS_ECOMMERCE))
  {
      $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
      $error = true;
  }




  if(!$error)
  {



   $data = $MyRequest->getRequest("card");

    $CardsEntity->uid($MySession->GetVar('id'));
    $CardsEntity->status(1);
    $CardsEntity->numero(substr($data["number"],-4));

    if($CardsModel->getData($CardsEntity->getArrayCopy())!= REGISTRO_SUCCESS)
    {


      $source = addCardConekta($MyRequest->getRequest("token"),$MySession->GetVar('id'));


      $CardsEntity->nombre($data["name"]);
      $CardsEntity->fecha(date('Y-m-d H:i:s'));
      $CardsEntity->token($source['id']);
      $id_tarjeta = $source['id'];

      $result = $CardsModel->save($CardsEntity->getArrayCopy());

      if($result == REGISTRO_SUCCESS)
      {


      }
      elseif($result == REGISTRO_ERROR)
      {
        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
        $error = true;
      }
      else
      {
          $error = true;
      }
    }
    else{
      $registro = $CardsModel->getRows();
       $id_tarjeta = $registro["token"];
    }
  }

}
else
{
    $CardsEntity->id($id_tarjeta);
    $CardsEntity->status(1);
    $CardsEntity->uid($MySession->GetVar('id'));
    $CardsModel->getData($CardsEntity->getArrayCopy());
    $registro = $CardsModel->getRows();
    $id_tarjeta = $registro["token"];
}
if(!$error)
{
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
         $error = true;
  }

  foreach($productos_comprados['productos'] as $producto)
  {
      $items[] = array("name" => $producto["nombre"],"unit_price" => $producto["precio"]*100,"quantity" => $producto["qty"]);
  }
  if($data['monto_envio'] > 0)
    {
        $items[] = array("name" => "Servicio de envio","unit_price" => $data["monto_envio"]*100,"quantity" => 1); 
    }
}

/*Aqui conecta*/

if(!$error)
{
    try{
          $order = \Conekta\Order::create(
          array(
            "line_items" => $items,
            "currency" => DATA_STORE_CONFIG['abreviatura'],
            "customer_info" => array(
                    "customer_id" => getCustomerConekta($MySession->GetVar('id'))
            ),
            "charges" => array(
                array(
                    "payment_method" => array(
                             "type" => "card",
                              "payment_source_id" => $id_tarjeta
                    )
                )
            )
          )
        );

    } catch (\Conekta\ErrorList $e) {
        $MyFlashMessage->setMsg("error",$e->getMessage());
        $MyRequest->redirect($MyRequest->getReferer());

    }
    catch (\Conekta\ParameterValidationError $e) {
        $MyFlashMessage->setMsg("error",$e->getMessage());
        $MyRequest->redirect($MyRequest->getReferer());

    }

    $referencia = json_encode(['id' => $order->id]);
    $status_pago = normalizeStatusTransaccion($order->payment_status);

       $MySession->SetVar('status_pago',$status_pago);
    

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

    
     $MySession->SetVar('cupon_checkout',array());
    $MySession->SetVar('checkout',array());
    $MyPedidoEntity->setId_direccion_envio(json_encode($direccion_envio));
    $MyPedidoEntity->setId_direccion_facturacion(json_encode($id_direccion_facturacion));
    $MyPedidoEntity->setFecha(date('Y-m-d H:i:s'));
    $MyPedidoEntity->setUid($MySession->GetVar('id'));
    $MyPedidoEntity->setStatus($status_pago);
    $MyPedidoEntity->setMetodo_pago("conekta_tarjeta");
    $MyPedidoEntity->setMetodo_envio($data['id_metodo_envio']);
    $MyPedidoEntity->setMonto_compra($productos_comprados['gran_total']);
    $MyPedidoEntity->setMonto_pagado($productos_comprados['gran_total']+$data['monto_envio']);
    $MyPedidoEntity->setSubtotal($productos_comprados['subtotal']);
    $MyPedidoEntity->setIva($productos_comprados['iva_total']);
    $MyPedidoEntity->setMonto_envio($data['monto_envio']);
    $MyPedidoEntity->setDescuento($productos_comprados['descuento']);
    $MyPedidoEntity->setCupon($cupon['id']);
    $MyPedidoEntity->setData_cupon(json_encode($cupon));
    $MyPedidoEntity->setReferencia($referencia);

    if($MyPedido->save($MyPedidoEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $pedido = $MyPedido->getUltimoID();
        $MySession->SetVar('id_pedido',$pedido);
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


        $campos = array("orden" => $pedido,
            "nombre" =>$MySession->GetVar('nombre'),
            "email" =>$MySession->GetVar('email'),
            'productos' =>$productos_html,
            'subtotal' => getFormatoPrecio($productos_comprados['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
            'descuento' => getFormatoPrecio($productos_comprados['descuento'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
            'envio' => getFormatoPrecio($data['monto_envio'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
            'metodo_envio' =>$envio_html,
            'iva' => getFormatoPrecio($productos_comprados['iva_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
            'gran_total' => getFormatoPrecio($productos_comprados['gran_total']+$data['monto_envio']-$productos_comprados['descuento'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),'metodo_pago' =>'Pago con tarjeta','status' => getStatusTransaccion($status_pago));


        
            $TemplateemailModel    = new \Base\model\TemplateemailModel;
            $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
            $TemplateemailEntity->id(getCoreConfig('ecommerce/conekta/email-order-tarjeta'));
            $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
            $registro  = $TemplateemailModel->getRows();


        sendEmail($campos,$registro);


        $ObserverManager->dispatch('finalizar_orden_ecommerce',[$pedido]);

    }
    else {
        // hubo un error al guardar.
    }

    $MyCarritoCompras->delete(getMyIdCarrito());
}

if($error)
{
  $MyRequest->redirect($MyRequest->getReferer());
}
$MyRequest->redirect($MyRequest->url(CONFIRMACION_CONEKTA_TARJETA));
?>
