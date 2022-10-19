<?php
function EliminarDireccionEcommerce($id,$status)
{
    global $MySession;
    $MyDireccion =  new \Ecommerce\model\direcciones();
    $MyDireccionEntity =  new \Ecommerce\entity\direcciones();
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_DIRECCIONES_ECOMMERCE))
    {
        $MyDireccionEntity->setId(addslashes($id));
        $MyDireccionEntity->setStatus($status);
        $MyDireccionEntity->setUid($MySession->GetVar('id'));
        if($MyDireccion->save($MyDireccionEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_direccion_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}

function EliminarTiendaEcommerce($id,$status)
{
    global $MySession;
    $EcommercetiendasModel =  new \Ecommerce\model\EcommercetiendasModel();
    $EcommercetiendasEntity =  new \Ecommerce\entity\EcommercetiendasEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_TIENDAS_ECOMMERCE))
    {
        $EcommercetiendasEntity->setId(addslashes($Tokenizer->decode($id)));
        $EcommercetiendasEntity->setStatus($status);
        
        if($EcommercetiendasModel->save($EcommercetiendasEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_tienda_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function EliminarTarjetaEcommerce($id,$status)
{
    global $MySession;
    $CardsModel =  new \Ecommerce\model\CardsModel();
    $CardsEntity =  new \Ecommerce\entity\CardsEntity();
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_TARJETAS_ECOMMERCE))
    {
        $CardsEntity->id(addslashes($id));
        $CardsEntity->status($status);
        $CardsEntity->uid($MySession->GetVar('id'));
        if($CardsModel->save($CardsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            $CardsModel->getData($CardsEntity->getArrayCopy());
            $registro = $CardsModel->getRows();
            if(getCoreConfig('ecommerce/conekta/enabled') == 1)
            {
                if(in_array('conekta_tarjeta',getCoreConfig('ecommerce/conekta/methods')))
                {
                    deleteCardConekta($registro['token'],$registro['uid']);
                }
            }
            if(getCoreConfig('ecommerce/openpay/enabled') == 1)
            {
                if(in_array('openpay_tarjeta',getCoreConfig('ecommerce/openpay/methods')))
                {
                    deleteCardOpenpay($registro['token'],$registro['uid']);
                }
            }
            if(getCoreConfig('ecommerce/sr-pago/enabled') == 1)
            {
                if(in_array('srpago_tarjeta',getCoreConfig('ecommerce/sr-pago/methods')))
                {
                    deleteCardSrpago($registro['token'],$registro['uid']);
                }
            }
             $respuesta["message"] = "success";
        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_tarjeta_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function EliminarDireccionFacturacionEcommerce($id,$status)
{
    global $MySession;
    $MyDireccion =  new \Ecommerce\model\direcciones_facturacion();
    $MyDireccionEntity =  new \Ecommerce\entity\direcciones_facturacion();
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_DIRECCIONES_ECOMMERCE))
    {
        $MyDireccionEntity->setId(addslashes($id));
        $MyDireccionEntity->setStatus($status);
        $MyDireccionEntity->setUid($MySession->GetVar('id'));
        if($MyDireccion->save($MyDireccionEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_direccion_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function eliminarProductoCarrito($id)
{
    global $MySession;
    $MyCarritoProducto =  new \Ecommerce\model\carrito_producto();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = array("error" => false);

    if($MyAccessList->MeDasChancePasar(CARRITO_ECOMMERCE))
    {
        if($MyCarritoProducto->delete(addslashes($Tokenizer->decode($id)),getMyIdCarrito()) == REGISTRO_SUCCESS)
        {
               $respuesta = getInfoCarrito();
               $cupon = $MySession->GetVar('cupon_checkout');
                if($cupon != false)
                {
                    $valida_cupo = validaCuponEcommerce($cupon['cupon']);
                    
                    if($valida_cupo['error'] == true){
                        ecommerce_removeCupon();
                    }
                }
                validaPromocionEcommerce();
        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function getInfoCarrito()
{
    $MyCarritoProducto =  new \Ecommerce\model\carrito_producto();
    $MyCarritoCompras =  new \Ecommerce\model\carrito();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyConfigure;

    $productos =  OBJETO_PRODUCTOS;
    $MyProducto =  new $productos();


    $registro = $MyCarritoCompras->getRows();
    $id_carrito = getMyIdCarrito();


    $respuesta = array("qty" => 0,"subtotal" => 0,"total"=> 0);
    $MyCarritoProducto->setTampag(100);
    if($MyCarritoProducto->getData("", $id_carrito) == REGISTRO_SUCCESS)
    {

        while($registro = $MyCarritoProducto->getRows())
        {

            $MyProducto->getInfoProducto($registro["id_producto"]);
            $_registro = $MyProducto->getRows();

            $imagen = "";
            $_img = getCoreConfig('ecommerce/product/placeholder');
            if($_img != "" && file_exists(PROJECT_DIR.$_img))
            {
              $imagen = imageResize($_img,50,50, true);
            }
           
            if(!empty($_registro["imagen"]))
            {
                $_imagen = json_decode($_registro["imagen"],true);
                
                if(is_array($_imagen))
                {
                    if(!empty($_imagen)){
                    
                        foreach($_imagen as $foto)
                        {
                           
                            if($foto['principal'] == 1)
                            {
                                if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE.'/'.$registro["id_producto"].'/'.$foto['img']))
                                {
                                    $imagen = imageResize($MyConfigure->getUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE."/".$registro["id_producto"].'/'.$foto['img'],50,50, true);  
                                }
                            }
                        }
                    }
                }
                else{
                    $imagen = imageResize($MyConfigure->getUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE."/".$registro["id_producto"].'/'.$_registro['imagen'],50,50, true);
                }
            }

            $respuesta["qty"] += $registro["qty"];

            $respuesta["subtotal"] += $_registro["precio"] * $registro["qty"];

            $respuesta["productos"][] = array("id" => $Tokenizer->token("productos",$registro["id"]), "id_producto" => $Tokenizer->token("productos",$registro["id_producto"]),"_id" => $registro['id'],"nombre" => $_registro["nombre"],"precio" => getFormatoPrecio($_registro["precio"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),"qty" =>  $registro["qty"],"img" => $imagen,"subtotal" => getFormatoPrecio($registro["qty"]*$_registro["precio"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),"caracteristicas" => json_decode($registro['caracteristicas'],true));

        }

        $parse_precio =  getCarrito();
        $respuesta["total"] = getFormatoPrecio($parse_precio['gran_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["subtotal"] = getFormatoPrecio($parse_precio['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["iva"] = getFormatoPrecio($parse_precio['iva_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        

    }

    return $respuesta;
}


function addProductoCarrito($producto,$qty=1,$caracteristicas="{}")
{
        $MyCarritoEntity =  new \Ecommerce\entity\carrito();
        $MyCarritoCompras =  new \Ecommerce\model\carrito();
        $MyCarritoProducto =  new \Ecommerce\model\carrito_producto();
        $MyCarritoProductoEntity =  new \Ecommerce\entity\carrito_producto();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        global $MyAccessList;
        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;


        $caracteristicas = json_decode($caracteristicas,true);

        if(!empty($caracteristicas))
        {
            foreach($caracteristicas as $k => $val){
                if($val['name'] == 'qty')
                {
                    unset($caracteristicas[$k]);
                }

            }
            
        }
        $caracteristicas = json_encode($caracteristicas);

        
        $respuesta = array("error" => false);

        if($MyAccessList->MeDasChancePasar(CARRITO_ECOMMERCE))
        {
            $id_carrito = getMyIdCarrito();
            if($id_carrito == 0)
            {
                $MyCarritoEntity->setCookie_id(session_id());
                if($MySession->LoggedIn())
                {
                    $MyCarritoEntity->setUid($MySession->GetVar("id"));

                }
                $MyCarritoCompras->save($MyCarritoEntity->getArrayCopy());
                $id_carrito = $MyCarritoCompras->getUltimoID();
            }
            //echo $id_carrito;
            if($MyCarritoProducto->getData("", $id_carrito,$Tokenizer->decode($producto),$caracteristicas) == REGISTRO_SUCCESS)
            {
                $registro = $MyCarritoProducto->getRows();

                $qty += $registro["qty"];
                $MyCarritoProductoEntity->setId($registro["id"]);


            }

            $ObserverManager = new \Franky\Core\ObserverManager;
            $ObserverManager->dispatch('prepara_producto_carrito',['id' => $Tokenizer->decode($producto),'qty' => $qty]);

            $MyCarritoProductoEntity->setId_producto($Tokenizer->decode($producto));
            $MyCarritoProductoEntity->setQty($qty);
            $MyCarritoProductoEntity->setCaracteristicas($caracteristicas);
            $MyCarritoProductoEntity->setId_carrito($id_carrito);

            if($MyCarritoProducto->save($MyCarritoProductoEntity->getArrayCopy()) == REGISTRO_SUCCESS)
            {

                $respuesta = getInfoCarrito();

            }
            else
            {
                $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_error_add");
                $respuesta["error"] = true;

            }
        }
        else
        {
             $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
             $respuesta["error"] = true;
        }

	return $respuesta;
}

function setQTYProductoCarrido($id,$qty)
{
        $MyCarritoProdcutoEntity =  new \Ecommerce\entity\carrito_producto();
	       $MyCarritoProducto =  new \Ecommerce\model\carrito_producto();
         $Tokenizer = new \Franky\Haxor\Tokenizer;
        global $MyAccessList;
        global $MyMessageAlert;
        global $MySession;
        $respuesta = array("error" => false,"total" => 0, "iva" => 0, "subtotal" => 0);

        if($MyAccessList->MeDasChancePasar(CARRITO_ECOMMERCE))
        {
            $id_carrito = getMyIdCarrito();
            if($MyCarritoProducto->getData($Tokenizer->decode($id), $id_carrito) == REGISTRO_SUCCESS)
            {
                $registro = $MyCarritoProducto->getRows();
                $ObserverManager = new \Franky\Core\ObserverManager;
                
                $ObserverManager->dispatch('prepara_producto_carrito',['id' => $registro['id_producto'],'qty' => $qty]);
    
                $MyCarritoProdcutoEntity->setId($Tokenizer->decode($id));
                $MyCarritoProdcutoEntity->setQty($qty);
                $MyCarritoProdcutoEntity->setId_carrito($id_carrito);

                if($MyCarritoProducto->save($MyCarritoProdcutoEntity->getArrayCopy())  == REGISTRO_SUCCESS)
                {

                    $respuesta = getInfoCarrito();
                    $cupon = $MySession->GetVar('cupon_checkout');
                    if($cupon != false)
                    {
                        $valida_cupo = validaCuponEcommerce($cupon['cupon']);
                        
                        if($valida_cupo['error'] == true){
                            ecommerce_removeCupon();
                        }
                    }
                    validaPromocionEcommerce();
                }
                else
                {
                    $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_error_cantidad");
                    $respuesta["error"] = true;
                }
            }
            else{
                $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
                $respuesta["error"] = true;
            }
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
            $respuesta["error"] = true;
        }

	return $respuesta;
}




function setconfigPago($id_pago)
{

    $MyCarritoModel = new \Ecommerce\model\carrito();
    $MyCarritoEntity = new \Ecommerce\entity\carrito();
    global $MyAccessList;
    global $MySession;
    global $MyMessageAlert;

    $respuesta = array("error" => false);

    if($MyAccessList->MeDasChancePasar(CARRITO_ECOMMERCE))
    {

        if($MyCarritoModel->getData("", $MySession->GetVar("id") ) == REGISTRO_SUCCESS)
        {

            $data = $MySession->GetVar('checkout');
            $data["id_pago"] = $id_pago;
            $MySession->SetVar('checkout',$data);
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_vacio");
            $respuesta["error"] = true;
        }
    }
    else
    {
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
        $respuesta["error"] = true;
    }

    return $respuesta;
}

function setDireccionCheckout($id_envio)
{
    global $MySession;
    
    $data = array('id_envio' => $id_envio,'direccion_envio' => '');
    
    
    
    $MyDireccion = new Ecommerce\model\direcciones();

    $MyDireccion->setTampag(1000);
    $MyDireccion->setOrdensql("fecha ASC");
    $MyDireccion->getData("",$MySession->GetVar('id'));
    $total	= $MyDireccion->getTotal();



    if($total > 0)
    {

        while($direccion_envio = $MyDireccion->getRows())
        {
            $data['direccion_envio'] = $direccion_envio;
            if($direccion_envio['id'] == $id_envio)
            {

                  $data['resumen_envio'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.envio.phtml',['direccion_envio' =>$direccion_envio]);
            }

        }
    }
    $MySession->SetVar('checkout',$data);

    return $data;
}

function setMetodoEnvioCheckout($id){
    global $MySession;
    $metodo_envio = getMetodosEnvio($id);
    $data = $MySession->GetVar('checkout');
    $data = array_merge($data,
            array('id_metodo_envio' => $id,'monto_envio' => $metodo_envio,'monto_envio_html' => getFormatoPrecio($metodo_envio,true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura'])));
    $metodo_envio = makeHTMLMetodosEnvio($id);
    
    $data['resumen_metodo_envio'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.metodo_envio.phtml',['metodo_envio' =>$metodo_envio]);   
    
    $MySession->SetVar('checkout',$data);
    
    return $data;
}
function setNuevaDireccionCheckout($data)
{
    global $MySession;
    $data2 = array();
    $data = json_decode($data,true);
    foreach($data as $node){
        $data2[$node["name"]] = $node["value"];
    }

    $data =array("direccion_envio" => $data2,
        'resumen_envio' =>   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.envio.phtml',['direccion_envio' =>$data2])
    );
    
    $MySession->SetVar('checkout',$data);

    return $data;
}
function setPickUpCheckout($id)
{
    global $MySession;

    $EcommercetiendasModel = new Ecommerce\model\EcommercetiendasModel();
    $EcommercetiendasEntity = new Ecommerce\entity\EcommercetiendasEntity();
   
    $EcommercetiendasModel->getData($id);
    $registro = $EcommercetiendasModel->getRows();
    $registro['horario'] = json_decode($registro['horario'],true);
    $EcommercetiendasEntity->exchangeArray($registro);
    $data = $MySession->GetVar('checkout');
    $data = array_merge($data,array("pickup" => $id,'direccion_pickup' => $EcommercetiendasEntity->getArrayCopy()));
    
    $metodo_pickup = getMetodoEnvioPickup();
    $metodo_envio = getMetodosEnvio($metodo_pickup['id']);

    $data['id_metodo_envio'] = $metodo_pickup['id'];
    $data['monto_envio'] = $metodo_envio;
    $data['monto_envio_html'] = getFormatoPrecio($metodo_envio,true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    $data['pickup']  = true;

    $MySession->SetVar('checkout',$data);
    $data['resumen_envio'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.pickup.phtml',['pickup' =>$registro]);

    return $data;
}


function setFacturacionCheckout($id_facturacion)
{
    global $MySession;

    $data = $MySession->GetVar('checkout');
    $data['direccion_facturacion'] = $id_facturacion;
    $data['direccion_facturacion'] = '';
    $MyDireccion = new Ecommerce\model\direcciones_facturacion();

    $MyDireccion->setTampag(1000);
    $MyDireccion->setOrdensql("fecha ASC");
    $MyDireccion->getData("",$MySession->GetVar('id'));
    $total	= $MyDireccion->getTotal();



    if($total > 0)
    {

        while($direccion_facturacion = $MyDireccion->getRows())
        {
            $data['direccion_facturacion'] = $direccion_facturacion;
            if($direccion_facturacion['id'] == $id_facturacion)
            {

                  $data['resumen_facturacion'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.facturacion.phtml',['direccion_facturacion' =>$direccion_facturacion]);
            }

        }
    }
    if($id_facturacion == 'no_requiere')
    {
         $data['resumen_facturacion'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.nofacturacion.phtml');

    }


    $MySession->SetVar('checkout',$data);

    return $data;
}


function setNuevaFacturacionCheckout($data)
{
    global $MySession;
    $data2 = $MySession->GetVar('checkout');
    $data = json_decode($data,true);
    $data3 = array();
    $data2["direccion_facturacion"] = '';
    foreach($data as $node){
        $data3[$node["name"]] = $node["value"];
    }

    $data2["direccion_facturacion"] = $data3;
    $data2['resumen_facturacion'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.facturacion.phtml',['direccion_facturacion' =>$data3]);
    $MySession->SetVar('checkout',$data2);

    return $data2;
}

function SetStatusPagoEcommerce($id,$status,$nota,$monto)
{
    global $MyAccessList;
    global $MySession;
    global $MyMessageAlert;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $pedidosModel    = new \Ecommerce\model\pedidos();
    $pedidosEntity   = new \Ecommerce\entity\pedidos();
    $TemplateemailModel    = new \Base\model\TemplateemailModel;
    $USERS = new \Base\model\USERS();
    $EcommercelogstatusModel    = new \Ecommerce\model\EcommercelogstatusModel();
    $EcommercelogstatusEntity   = new \Ecommerce\entity\EcommercelogstatusEntity();
    $ObserverManager = new \Franky\Core\ObserverManager;


    $respuesta = array("error" => false);

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PEDIDOS))
    {
        $pedidosEntity->setId($Tokenizer->decode($id));

        $pedidosModel->getData($pedidosEntity->getArrayCopy());

        $pedido = $pedidosModel->getRows();

        $data = json_encode(['nota' => $nota,"monto" => $monto]);

        $pedidosEntity->setStatus($status);

        if($pedidosModel->save($pedidosEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_cambiar_status_pedido_success");
              $respuesta["status"] = getStatusTransaccion($status);

              $EcommercelogstatusEntity->status($status);
              $EcommercelogstatusEntity->auto(0);
              $EcommercelogstatusEntity->id_user($MySession->GetVar('id'));
              $EcommercelogstatusEntity->fecha(date('Y-m-d H:i:s'));
              $EcommercelogstatusEntity->id_pedido($Tokenizer->decode($id));
              $EcommercelogstatusEntity->info($data);
              $EcommercelogstatusModel->save($EcommercelogstatusEntity->getArrayCopy());

              $detalle_pedido = getPedido($Tokenizer->decode($id));

              if($USERS->getData($detalle_pedido['uid'])==REGISTRO_SUCCESS)
              {

                $dataUser = $USERS->getRows();

                $productos_html = render(PROJECT_DIR.'/modulos/ecommerce/diseno/email/productos.phtml',['items' =>$detalle_pedido['productos']]);


                $campos = array("orden" => $Tokenizer->decode($id),"nombre" =>$detalle_pedido['nombre'],'productos' =>$productos_html,"email" => $dataUser['email'],
                'gran_total' => getFormatoPrecio($detalle_pedido['monto_compra'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),'metodo_pago' =>$detalle_pedido['metodo_pago'],"status" => getStatusTransaccion($status));


                $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
                $TemplateemailEntity->id(getCoreConfig('ecommerce/ventas/email-template-cambiostatus'));
                $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
                $registro  = $TemplateemailModel->getRows();

                sendEmail($campos,$registro);

                $ObserverManager->dispatch('change_status_pago',[$Tokenizer->decode($id)]);


              }
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("ecommerce_cambiar_status_pedido_error");
            $respuesta["error"] = true;
        }
    }
    else
    {
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
        $respuesta["error"] = true;
    }


    return $respuesta;
}

function pay_free()
{

        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;
        $respuesta = array("error" => false,"html" => "");

        if($MySession->LoggedIn())
        {
           $token = getToken("pay_free");
           $MySession->SetVar('pay_free',$token);
          $html = "<a class=\"_btn _primary_checkout\" href=\"".$MyRequest->url(CONFIRMACION_PAY_FREE)."\">Terminar compra</a>";

            $respuesta["html"] = $html;
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
            $respuesta["error"] = true;
        }

	return $respuesta;
}

function loadMetodosEnvio(){
    global $MySession;
    $metodos_envio = makeHTMLMetodosEnvio();
    $carrito = getCarrito();
    $data = $MySession->GetVar('checkout');
  
    $data['gran_total'] = $carrito['gran_total'];
    $MySession->SetVar('checkout',$data);
  
    if(getCoreConfig('ecommerce/pick-up/enabled') == 1)
    {
        $direcciones_envio["pick-up"] = getCoreConfig('ecommerce/pick-up/titulo');
        $pickupForm = new \Ecommerce\Form\pickupForm("frmpickup");
        $pickuppoints = getPickUpPoints();
        $pickupForm->addPickuppoints($pickuppoints);
        $pickupForm->addSubmit();
    }


    if($carrito['envio_requerido'] == 1)
    {
        $MetodoEnvioCheckoutForm = new \Ecommerce\Form\checkoutForm("frm_metodo_envio");
        $MetodoEnvioCheckoutForm->addMetodoEnvio($metodos_envio);
        $MetodoEnvioCheckoutForm->addSubmit();
        return array('labelpickup' =>getCoreConfig('ecommerce/pick-up/titulo'),'envio_requerido' => 1,'html' => render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/frm.metodos_envio.phtml',['MetodoEnvioCheckoutForm' => $MetodoEnvioCheckoutForm,'pickupForm' => $pickupForm]));
    }
    
    return array('envio_requerido' => 0,'html' => '');
    
}

function loadMetodosPago(){
    global $MySession;
    $data = $MySession->GetVar('checkout');
    $PagoCheckoutForm = new \Ecommerce\Form\checkoutForm("frm_pago");
    
    
    $CoreConfig  = new \Base\model\CoreConfig();
    $core_config = $CoreConfig->getMap('ecommerce');
    foreach($core_config as $key_config => $val_config):

        foreach($val_config['config'] as $key =>$config):
              if($config['path'] == "ecommerce/conekta/methods"):
                  $metodos_conekta = $config['data'];
              endif;
              if($config['path'] == "ecommerce/openpay/methods"):
                  $metodos_openpay = $config['data'];
              endif;
              if($config['path'] == "ecommerce/sr-pago/methods"):
                $metodos_srpago = $config['data'];
            endif;
        endforeach;
    endforeach;


    $metodos_de_pago = array();
   // print_r($data);
    if($data['gran_total'] > 0)
    {
        if(getCoreConfig('ecommerce/paypal/enabled') == 1)
        {
            $metodos_de_pago["pago_paypal"] = "payPal";
        }
        if(getCoreConfig('ecommerce/conekta/enabled') == 1)
        {
            $metodos = getCoreConfig('ecommerce/conekta/methods');
            if(!empty($metodos)):
                foreach($metodos as $k)
                {
                  $metodos_de_pago[$k] = $metodos_conekta[$k];
                }

            endif;
        }
        if(getCoreConfig('ecommerce/openpay/enabled') == 1)
        {
            $metodos = getCoreConfig('ecommerce/openpay/methods');
            if(!empty($metodos)):
                foreach($metodos as $k)
                {
                  $metodos_de_pago[$k] = $metodos_openpay[$k];
                }

            endif;
        }
        if(getCoreConfig('ecommerce/sr-pago/enabled') == 1)
        {
            $metodos = getCoreConfig('ecommerce/sr-pago/methods');
            if(!empty($metodos)):
                foreach($metodos as $k)
                {
                  $metodos_de_pago[$k] = $metodos_srpago[$k];
                }

            endif;
        }
    }
    else{
        $metodos_de_pago["pay_free"] = "No requiere pago";
        $PagoCheckoutForm->setAtributoInput('id_pago','value','pay_free');
    }
    $PagoCheckoutForm->addMetodoPago($metodos_de_pago);
    $PagoCheckoutForm->addSubmit();
    
    return array('html' => render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/frm.metodos_pago.phtml',['PagoCheckoutForm' => $PagoCheckoutForm]));
}


function ajax_setInputsConfigPromo($id,$promocion){
    global $MySession;
    
    $respuesta = ['html' => ''];
    
    $EcommercecuponesModel             = new \Ecommerce\model\EcommercecuponesModel();
    $EcommercecuponesEntity             = new \Ecommerce\entity\EcommercecuponesEntity();
    $EcommercepromocionesclassModel = new Ecommerce\model\EcommercepromocionesclassModel();
    $EcommercepromocionesclassEntity = new Ecommerce\entity\EcommercepromocionesclassEntity();
   
    
    $Form = new \Franky\Form\Form();
    
    $_data = $MySession->GetVar('data_cupon');
    if(!empty($id))
    {
        $EcommercecuponesEntity->id($id);
        $EcommercecuponesModel->getData($EcommercecuponesEntity->getArrayCopy());
        $data = $EcommercecuponesModel->getRows();	
        $_data = json_decode($data['data'],true);
    }
    
   
    $EcommercepromocionesclassEntity->id($promocion);
    $EcommercepromocionesclassModel->getData($EcommercepromocionesclassEntity->getArrayCopy());
    $registro = $EcommercepromocionesclassModel->getRows();
  
    $class = new $registro['dataClass'];
    $form = $class->getForm();
    if(!empty($form))
    {
        foreach ($form as $input):
        $Form->add($input);
        endforeach;
        
        $Form->setData($_data);
        
        $respuesta['html'] = $Form->getAllRow();
    }
    return $respuesta;
    
}

function EliminarCuponesEcommerce($id,$status)
{
    global $MySession;
    $EcommercecuponesModel             = new \Ecommerce\model\EcommercecuponesModel();
    $EcommercecuponesEntity             = new \Ecommerce\entity\EcommercecuponesEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_CUPONES_ECOMMERCE))
    {
        $EcommercecuponesEntity->id(addslashes($Tokenizer->decode($id)));
        $EcommercecuponesEntity->status($status);

        if($EcommercecuponesModel->save($EcommercecuponesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_cupon_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function EliminarPromocionEcommerce($id,$status)
{
    global $MySession;
    $EcommercepromocionesModel             = new \Ecommerce\model\EcommercepromocionesModel();
    $EcommercepromocionesEntity             = new \Ecommerce\entity\EcommercepromocionesEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PROMOCIONES_ECOMMERCE))
    {
        $EcommercepromocionesEntity->id(addslashes($Tokenizer->decode($id)));
        $EcommercepromocionesEntity->status($status);

        if($EcommercepromocionesModel->save($EcommercepromocionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_promocion_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function ecommerce_setCupon($cupon)
{

    global $MyMessageAlert;
    $respuesta = ['html' => ''];
    
    $valida_cupo = validaCuponEcommerce($cupon);
    validaPromocionEcommerce();
    if($valida_cupo['error'] == true){
        $respuesta['error'] =true;
        $respuesta['message'] = $MyMessageAlert->Message($valida_cupo['message']);
        return $respuesta;
    }
    $respuesta['html'] = render(PROJECT_DIR.'/modulos/ecommerce/diseno/carrito/cupon.phtml',['cupon' => $cupon]);
    
    return $respuesta;
}

function ecommerce_removeCupon()
{
    global $MySession;
    $MySession->UnsetVar('cupon_checkout');
    return null;
}


function getInfoTotalsCheckout()
{
    global $MySession;
    $respuesta = null;
    
    $cupon = $MySession->GetVar('cupon_checkout');
    if($cupon != false)
    {
        $valida_cupo = validaCuponEcommerce($cupon['cupon']);
        
        if($valida_cupo['error'] == true){
            ecommerce_removeCupon();
        }
    }
    validaPromocionEcommerce();
    $parse_precio   =  getCarrito();
    $respuesta["total"] =$parse_precio['gran_total'];
    $respuesta["subtotal"] = getFormatoPrecio($parse_precio['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    $respuesta["iva"] = getFormatoPrecio($parse_precio['iva_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    
    if($parse_precio['descuento'] > 0)
    {
        $respuesta["total"] = $respuesta['total']-$parse_precio['descuento'];
        $respuesta['descuento'] = getFormatoPrecio($parse_precio['descuento'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    }
    $respuesta["total"] = getFormatoPrecio($respuesta['total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    return $respuesta;
}



function getInfoTotalsCheckout2()
{
    global $MySession;
    $respuesta = null;
    
    $cupon = $MySession->GetVar('cupon_checkout');
    if($cupon != false)
    {
        $valida_cupo = validaCuponEcommerce($cupon['cupon']);
        
        if($valida_cupo['error'] == true){
            ecommerce_removeCupon();
        }
    }
    validaPromocionEcommerce();
    $data = $MySession->GetVar('checkout');
    
    $productos_comprados = getCarrito();
    
    $respuesta['gran_total'] = $productos_comprados['gran_total']; 
     
    if($productos_comprados['descuento'] > 0)
    {
        $respuesta['gran_total'] -= $productos_comprados['descuento']; 
        $respuesta['descuento'] = getFormatoPrecio($productos_comprados['descuento'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    }
    
    if(isset($data['monto_envio']))
    {
        $respuesta['gran_total'] += $data['monto_envio']; 
        $respuesta["monto_envio"] = getFormatoPrecio($data['monto_envio'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        
    }
    $respuesta['gran_total'] = getFormatoPrecio($respuesta['gran_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']); 
     
    $respuesta["subtotal"] = getFormatoPrecio($productos_comprados['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
    $respuesta["iva"] = getFormatoPrecio($productos_comprados['iva_total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
   
    

    return $respuesta;
}
/******************************** EJECUTA *************************/
$MyAjax->register("EliminarDireccionEcommerce");
$MyAjax->register("EliminarDireccionFacturacionEcommerce");
$MyAjax->register("EliminarTarjetaEcommerce");
$MyAjax->register("eliminarProductoCarrito");
$MyAjax->register("addProductoCarrito");
$MyAjax->register("setQTYProductoCarrido");
$MyAjax->register("setconfigPago");
$MyAjax->register("getInfoCarrito");
$MyAjax->register("setDireccionCheckout");
$MyAjax->register("setNuevaDireccionCheckout");
$MyAjax->register("setFacturacionCheckout");
$MyAjax->register("setNuevaFacturacionCheckout");
$MyAjax->register("SetStatusPagoEcommerce");
$MyAjax->register("pay_free");
$MyAjax->register("loadMetodosEnvio");
$MyAjax->register("setMetodoEnvioCheckout");
$MyAjax->register("loadMetodosPago");
$MyAjax->register("ajax_setInputsConfigPromo");
$MyAjax->register("EliminarCuponesEcommerce");
$MyAjax->register("ecommerce_setCupon");
$MyAjax->register("ecommerce_removeCupon");
$MyAjax->register("getInfoTotalsCheckout");
$MyAjax->register("getInfoTotalsCheckout2");
$MyAjax->register("EliminarTiendaEcommerce");
$MyAjax->register("setPickUpCheckout");
$MyAjax->register("EliminarPromocionEcommerce");
?>
