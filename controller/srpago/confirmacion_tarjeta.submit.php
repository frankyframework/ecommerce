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

$CardsModel        = new CardsModel();
$CardsEntity       = new CardsEntity();
$ObserverManager = new ObserverManager;
$ObserverManager->dispatch('prepara_orden_ecommerce',[]);

$data = $MySession->GetVar('checkout');

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
if($MySession->GetVar('tarjeta_srpago') == "" )
{
  $error = true;
}
$MySession->UnsetVar('tarjeta_srpago');

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
      

    $CardsEntity->uid($MySession->GetVar('id'));
    $CardsEntity->status(1);
    $CardsEntity->numero(substr($MyRequest->getRequest("number"),-4));


    if($CardsModel->getData($CardsEntity->getArrayCopy())!= REGISTRO_SUCCESS)
    {

      $CardsEntity->nombre($MyRequest->getRequest("holder_name"));
      $source = addCardSrpago($MyRequest->getRequest("tokenInput"),$MySession->GetVar('id'));
      
  
     
      $CardsEntity->fecha(date('Y-m-d H:i:s'));
      $CardsEntity->token($source['id']);
      $id_tarjeta = $source['id'];


      if(empty($id_tarjeta))
      {
        $MyRequest->redirect($MyRequest->getReferer());
        die;
      }
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
}

if(!$error)
{

    


    try{
      $order_id = md5(time().$MySession->GetVar('id'));
      
      $chargeParams = array(
        "amount"=> $productos_comprados['gran_total'] + $data['monto_envio'] - (isset($productos_comprados['descuento']) ? $productos_comprados['descuento'] : 0),
        "description" => "Cargo a tarjeta Orden $order_id",
        "reference"=> $order_id,
        "ip"=> $MyRequest->getIP(),
        "source"=>$id_tarjeta
        );
        //print_r($chargeParams);
        
        $fullname = explode(" ",$MySession->GetVar('nombre'));
        $nombres = "";
        $middlename = "";
        if(count($fullname) >= 3)
        {
            
            $apellido_materno = $fullname[count($fullname) - 1];
            $apellido_paterno = $fullname[count($fullname) - 2];
            unset($fullname[count($fullname) - 1]);
            unset($fullname[count($fullname) - 2]);
            if(count($fullname) > 1)
            {
                $nombres = $fullname[0];
                $middlename = $fullname[1];
            }
            
        }
        if(count($fullname) == 2)
        {
            $apellido_materno = "";
            $apellido_paterno = $fullname[count($fullname) - 1];
            $nombres = $fullname[0];
        }

        if(count($fullname) == 1)
        {
            $apellido_materno = "";
            $apellido_paterno = "";
            $nombres = $fullname[0];
        }

        $metadata = array(
            "member" => [
                "memberLoggedIn" => "Si",
                "memberFullName" => $MySession->GetVar('nombre'),
                "memberFirstName" => $nombres,
                "memberMiddleName" => $middlename,
                "memberLastName" => $apellido_paterno,
                "memberEmailAddress" => $MySession->GetVar('email'),
                "memberAddressLine1" => $data['direccion_envio']['calle'],
                "memberAddressLine2" => $data['direccion_envio']['colonia'],
                "memberCity" => $data['direccion_envio']['ciudad'],
                "memberState" => $data['direccion_envio']['estado'],
                "memberCountry" => 'MX',
                "memberPostalCode" => $data['direccion_envio']['cp'],
                "memberPhone" => $data['direccion_envio']['telefono'],
            ],
            "shipping" => [
                "shippingFirstName" => $nombres,
                "shippingMiddleName" => $middlename,
                "shippingLastName" => $apellido_paterno,
                "shippingCharges" => (isset($data['monto_envio']) ? $data['monto_envio']: 0),
                "shippingEmailAddress" => $MySession->GetVar('email'),
                "shippingAddress" => $data['direccion_envio']['calle'],
                "shippingAddress2" => $data['direccion_envio']['colonia'],
                "shippingCity" => $data['direccion_envio']['ciudad'],
                "shippingState" => $data['direccion_envio']['estado'],
                "shippingCountry" => 'MX',
                "shippingPostalCode" => $data['direccion_envio']['cp'],
                "shippingPhoneNumber" => $data['direccion_envio']['telefono'],
                "shippingMethod" => strip_tags(makeHTMLMetodosEnvio($data['id_metodo_envio'],0,0)),
                //"shippingDeadline" => "2015-08-01"
            ],
            "billing" => [
                "billingFirstName-D" => $nombres,
                "billingMiddleName-D" => $middlename,
                "billingLastName-D" => $apellido_paterno,
                "billingEmailAddress" => $MySession->GetVar('email'),
                "billingAddress-D" => $data['direccion_envio']['calle'],
                "billingAddress2-D" => $data['direccion_envio']['colonia'],
                "billingCity-D" => $data['direccion_envio']['ciudad'],
                "billingState-D" => $data['direccion_envio']['estado'],
                "billingCountry-D" => 'MX',
                "billingPostalCode-D" => $data['direccion_envio']['cp'],
                "billingPhoneNumber-D" => $data['direccion_envio']['telefono'],
            ]

            );

            if(isset($data["id_facturacion"]) && is_numeric($data["id_facturacion"])){
                
                $metadata["billing"] = [
                    "billingFirstName-D" => $nombres,
                    "billingMiddleName-D" => $middlename,
                    "billingLastName-D" => $apellido_paterno,
                    "billingEmailAddress" => $MySession->GetVar('email'),
                    "billingAddress-D" => $data['direccion_facturacion']['calle'],
                    "billingAddress2-D" => $data['direccion_facturacion']['colonia'],
                    "billingCity-D" => $data['direccion_facturacion']['ciudad'],
                    "billingState-D" => $data['direccion_facturacion']['estado'],
                    "billingCountry-D" => 'MX',
                    "billingPostalCode-D" => $data['direccion_facturacion']['cp'],
                    "billingPhoneNumber-D" => $data['direccion_facturacion']['telefono'],
                ];
            }
            foreach($productos_comprados['productos'] as $producto)
            {
                
                $metadata["items"]['item'][] = [
                    "itemNumber" => $producto["sku"],
                    "itemDescription" => $producto["nombre"],
                    "itemPrice" => $producto["precio_sin_iva"],
                    "itemQuantity" => $producto["qty"],
                    "itemTax" => (string)$producto["iva"],
                    //"itemMeasurementUnit" => "PZ",
                    //"itemBrandName" => "SR.Pago",
                    //"itemCategory" => "TI"
                ];
            }
        
//print_r(json_encode($metadata)); die;
    
        $order = pagoTarjeta($chargeParams,$metadata);
          
    } catch (\Exception $e) {
        $MyFlashMessage->setMsg("error",$e->getMessage());
        $MyRequest->redirect($MyRequest->getReferer());
       
        
    }



    if(!isset($order['result']['status']))
    {
        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("ecommerce_error_transaction"));
        $MyRequest->redirect($MyRequest->getReferer());
        
        die;
    }


    $referencia = json_encode(
            [
            'id' => $order['result']['transaction'],
            'status' => $order['result']['status'],
            'authorization' => $order['result']['authorization_code'],
            'order_id' => $order_id,
            ]
            );
    $status_pago = normalizeStatusTransaccion(getStatusTransaccionSrpago($order['result']['status']));

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


    $MySession->SetVar('cupon_checkout',array());
    $MySession->SetVar('checkout',array());
    $MyPedidoEntity->setId_direccion_envio(json_encode($direccion_envio));
    $MyPedidoEntity->setId_direccion_facturacion(json_encode($id_direccion_facturacion));
    $MyPedidoEntity->setFecha(date('Y-m-d H:i:s'));
    $MyPedidoEntity->setUid($MySession->GetVar('id'));
    $MyPedidoEntity->setStatus($status_pago);
    $MyPedidoEntity->setMetodo_pago("srpago_tarjeta");
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
            'productos' => $productos_html,
            'subtotal' => getFormatoPrecio($productos_comprados['subtotal']),
            'iva' => getFormatoPrecio($productos_comprados['iva_total']),
            'envio' => getFormatoPrecio($data['monto_envio']),
            'descuento' => getFormatoPrecio($productos_comprados['descuento']),
            'gran_total' => getFormatoPrecio($productos_comprados['gran_total']-$productos_comprados['descuento']+$data['monto_envio']),
            'metodo_pago' =>'Pago con tarjeta',
            'metodo_envio' =>$envio_html,
            'status' => getStatusTransaccion($status_pago));


        $TemplateemailModel    = new \Base\model\TemplateemailModel;
        $SecciontransaccionalEntity    = new \Base\entity\SecciontransaccionalEntity;
        $SecciontransaccionalEntity->friendly('nueva-orden-de-compra');
        $TemplateemailModel->setOrdensql('id DESC');
        $TemplateemailModel->getData([],$SecciontransaccionalEntity->getArrayCopy());

        $registro  = $TemplateemailModel->getRows();

        sendEmail($campos,$registro);

         $ObserverManager = new ObserverManager;

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
$MyRequest->redirect($MyRequest->url(CONFIRMACION_SRPAGO_TARJETA));
?>
