<?php

use Ecommerce\model\CarritoModel;

function EliminarDireccionEcommerce($id,$status)
{
    global $MySession;
    $MyDireccion =  new \Ecommerce\model\EcommerceDireccionesModel();
    $MyDireccionEntity =  new \Ecommerce\entity\EcommerceDireccionesEntity();
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar("administrar_direcciones_ecommerce"))
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

    if($MyAccessList->MeDasChancePasar("administrar_tiendas_ecommerce"))
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

function EliminarDireccionFacturacionEcommerce($id,$status)
{
    global $MySession;
    $MyDireccion =  new \Ecommerce\model\EcommerceDireccionesFacturacionModel();
    $MyDireccionEntity =  new \Ecommerce\entity\EcommerceDireccionesFacturacionEntity();
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar("administrar_direcciones_ecommerce"))
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
    $MyCarritoProducto =  new \Ecommerce\model\CarritoProductoModel();
    $MyCarritoModel =  new \Ecommerce\model\CarritoModel();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $ObserverManager = new \Franky\Core\ObserverManager;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = array("error" => false);
    $MyCarritoEntity = getMyIdCarrito();
   
    if($MyCarritoProducto->getData(addslashes($Tokenizer->decode($id)),$MyCarritoEntity->getId()) == REGISTRO_SUCCESS)
    {
        $registro = $MyCarritoProducto->getRows();   

        $MyCarritoProducto->delete(addslashes($Tokenizer->decode($id)),$MyCarritoEntity->getId());

        $ObserverManager->dispatch('change_quote',[]);

        $respuesta = getInfoCarrito();
    }
    else
    {
        $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_error_delete");
        $respuesta["error"] = true;
    }
    

    return $respuesta;
}


function addProductoCarrito($producto,$qty=1,$caracteristicas="{}")
{
        $productos =  OBJETO_PRODUCTOS;
        $MyProducto =  new $productos();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $MyCarritoCompras =  new \Ecommerce\model\CarritoModel();
        $MyCarritoProducto =  new \Ecommerce\model\CarritoProductoModel();
        $MyCarritoProductoEntity =  new \Ecommerce\entity\CarritoProductoEntity();
        $MyCarritoEntity =  new \Ecommerce\entity\CarritoEntity;

        global $MyAccessList;
        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;
        global $MyConfigure;

        $MyProducto->getInfoProducto($Tokenizer->decode($producto));
        $productData = $MyProducto->getRows();
    
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

        
        $ObserverManager = new \Franky\Core\ObserverManager;
        $ObserverManager->dispatch('prepara_producto_carrito',['id' => $Tokenizer->decode($producto),'n' => $qty]);

        $carritoData = getMyIdCarrito();
        if($carritoData == 0)
        {
            $MyCarritoEntity->setCookieId(session_id());
   
            $MyCarritoEntity->setCreatedAt(date('Y-m-d'));
            $MyCarritoEntity->setActive(1);
            if($MySession->LoggedIn()) {
                $MyCarritoEntity->setUid($MySession->GetVar('id'));
            }
            $MyCarritoCompras->save($MyCarritoEntity->getArrayCopy());
            $id_carrito = $MyCarritoCompras->getUltimoID();
        } else {
            $MyCarritoEntity = $carritoData;
            $MyCarritoEntity->setUpdateAt(date('Y-m-d'));
            $MyCarritoCompras->save($MyCarritoEntity->getArrayCopy());
            $id_carrito = $carritoData->getId();
        }
        //echo $id_carrito;
        if($MyCarritoProducto->getData("", $id_carrito,$Tokenizer->decode($producto),$caracteristicas) == REGISTRO_SUCCESS)
        {
            $registro = $MyCarritoProducto->getRows();

            $qty += $registro["qty"];
            $MyCarritoProductoEntity->setId($registro["id"]);
            
            $MyCarritoProductoEntity->setUpdateAt(date('Y-m-d H:i:s'));

        } else {
            $MyCarritoProductoEntity->setCreatedAt(date('Y-m-d'));
        }

        $imagen = "";
        $_img = getCoreConfig('ecommerce/product/placeholder');
        if($_img != "" && file_exists(PROJECT_DIR.$_img))
        {
            $imagen = imageResize($_img,50,50, true);
        }
        
        if(!empty($productData["imagen"]))
        {
            $_imagen = json_decode($productData["imagen"],true);
         
            if(is_array($_imagen))
            {
                if(!empty($_imagen)){
                
                    foreach($_imagen as $foto)
                    {
                        
                        if($foto['principal'] == 1)
                        {
                           if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE.'/'.$Tokenizer->decode($producto).'/'.$foto['img']))
                            {
                                $imagen = imageResize($MyConfigure->getUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE."/".$Tokenizer->decode($producto).'/'.$foto['img'],50,50, true);  
                            }
                        }
                    }
                }
            }
            else{
                $imagen = imageResize($MyConfigure->getUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE."/".$Tokenizer->decode($producto).'/'.$_imagen,50,50, true);
            }
        }
        $iva = DATA_STORE_CONFIG['iva'];
        $price = parsePrecio($productData['price']*$qty,$iva,$productData['incluye_iva']);
        
        
        $link = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $productData['url_key']]);
        $MyCarritoProductoEntity->setIdProduct($Tokenizer->decode($producto));
        $MyCarritoProductoEntity->setQty($qty);
        $MyCarritoProductoEntity->setPrice($productData['price']);
        $MyCarritoProductoEntity->setTotal($price['total']);
        $MyCarritoProductoEntity->setData($caracteristicas);
        $MyCarritoProductoEntity->setQuoteId($id_carrito);
        $MyCarritoProductoEntity->setSku($productData['sku']);
        $MyCarritoProductoEntity->setPriceDiscount(0);
        $MyCarritoProductoEntity->setTotalDiscount(0);
        $MyCarritoProductoEntity->setName($productData['nombre']);
        $MyCarritoProductoEntity->setEnvioRequerido($productData['envio_requerido']);
        $MyCarritoProductoEntity->setUrl($link);
        $MyCarritoProductoEntity->setImage($imagen);
        if(!empty($MyCarritoProductoEntity->getCustomPrice())) {
            $customPrice = parsePrecio($MyCarritoProductoEntity->getCustomPrice()*$qty,$iva,$productData['incluye_iva']);
            $MyCarritoProductoEntity->setTotalCustomPrice($customPrice['total']);
        }
        if($MyCarritoProducto->save($MyCarritoProductoEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            $ObserverManager->dispatch('change_quote',[]);

            $respuesta = getInfoCarrito();

        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_error_add");
            $respuesta["error"] = true;

        }
    

	return $respuesta;
}

function setQTYProductoCarrido($id,$qty)
{
        $MyCarritoProductoEntity =  new \Ecommerce\entity\CarritoProductoEntity();
	    $MyCarritoProducto =  new \Ecommerce\model\CarritoProductoModel();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $productos =  OBJETO_PRODUCTOS;
        $MyProducto =  new $productos();
        global $MyMessageAlert;


        $respuesta = array("error" => false,"total" => 0, "iva" => 0, "subtotal" => 0);
        
  
            $MyCarritoEntity = getMyIdCarrito();
            if($MyCarritoProducto->getData($Tokenizer->decode($id), $MyCarritoEntity->getId()) == REGISTRO_SUCCESS)
            {
                $registro = $MyCarritoProducto->getRows();
                $ObserverManager = new \Franky\Core\ObserverManager;
                
                $MyProducto->getInfoProducto($registro['id_product']);
                $productData = $MyProducto->getRows();
                $ObserverManager->dispatch('prepara_producto_carrito',['id' => $registro['id_product'],'n' => $qty]);
                $iva = DATA_STORE_CONFIG['iva'];
                $price = parsePrecio($productData['price']*$qty,$iva,$productData['incluye_iva']);
                $MyCarritoProductoEntity->setId($Tokenizer->decode($id));
                $MyCarritoProductoEntity->setQty($qty);
                $MyCarritoProductoEntity->setQuoteId($MyCarritoEntity->getId());
                $MyCarritoProductoEntity->setPrice($productData['price']);
                $MyCarritoProductoEntity->setEnvioRequerido($productData['envio_requerido']);
                $MyCarritoProductoEntity->setTotal($price['total']);
                $MyCarritoProductoEntity->setTotalDiscount(0);
                $MyCarritoProductoEntity->setPriceDiscount(0);
                if(!empty($MyCarritoProductoEntity->getCustomPrice())) {
                    $customPrice = parsePrecio($MyCarritoProductoEntity->getCustomPrice()*$qty,$iva,$productData['incluye_iva']);
                    $MyCarritoProductoEntity->setTotalCustomPrice($customPrice['total']);
                }

                if($MyCarritoProducto->save($MyCarritoProductoEntity->getArrayCopy())  == REGISTRO_SUCCESS)
                {
                    $ObserverManager->dispatch('change_quote',[]);
                    $respuesta = getInfoCarrito();
                  
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
 

	return $respuesta;
}





function setDireccionCheckout($id_envio)
{
    global $MySession;
    $data = ['resumen_envio' => ""];
    $MyDireccion = new Ecommerce\model\EcommerceDireccionesModel();
    $CarritoModel = new Ecommerce\model\CarritoModel();
    $MyDireccion->setTampag(1);
    $MyDireccion->setOrdensql("created_at ASC");
    $MyDireccion->getData($id_envio,$MySession->GetVar('id'));
    $total	= $MyDireccion->getTotal();

    if($total > 0)
    {
        $data = $MyDireccion->getRows();
        $MyCarritoEntity = getMyIdCarrito();
        $MyCarritoEntity->setShippingAddres(json_encode($data));
        $CarritoModel->save($MyCarritoEntity->getArrayCopy());
        $data['resumen_envio'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.envio.phtml',['direccion_envio' => $data]);
            
    }

    return $data;
}


function setCustomerDataCheckout($nombre, $email)
{
    global $MySession;
    $MyCarritoModel = new \Ecommerce\model\CarritoModel;
    $MyCarritoEntity = getMyIdCarrito();
    $MyCarritoEntity->setName($nombre);
    $MyCarritoEntity->setEmail($email);
    $MyCarritoModel->save($MyCarritoEntity->getArrayCopy());
    $data['resumen_customer'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.customer.phtml',['nombre' => $nombre, 'email' => $email]);
      

    return $data;
}


function setconfigPago($idPago)
{

    $MyCarritoModel = new \Ecommerce\model\CarritoModel;
    $MyCarrito = getMyIdCarrito();
    $paymentMethodsHTML = makeHTMLMetodosPago($idPago);
    global  $MyMessageAlert;
    $respuesta = array("error" => false);

   if(empty($paymentMethodsHTML)){
        $respuesta["message"] = $MyMessageAlert->Message("ecommerce_error_payment_method");
        $respuesta["error"] = true;
   } else {

        if(!empty($MyCarrito))
        {

            $MyCarrito->setPaymentMethod($idPago);
            $MyCarritoModel->save($MyCarrito->getArrayCopy());
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("ecommerce_carrito_vacio");
            $respuesta["error"] = true;
        }
    }

    return $respuesta;
}

function setMetodoEnvioCheckout($id){
    
    $MyCarritoModel = new \Ecommerce\model\CarritoModel;
    $MyCarrito = getMyIdCarrito();
    $metodo_envio = makeHTMLMetodosEnvio($id);
    $MyCarrito->setShippingMethod($id);
    $MyCarrito->setShippingData(json_encode($metodo_envio));
    $price = parsePrecio($metodo_envio['price'],$metodo_envio['iva'],1);
    $MyCarrito->setShippingPrice($price['total']);
    $MyCarrito->setShippingSubtotal($price['subtotal']);
    $MyCarrito->setShippingTax($price['total']-$price['subtotal']);
    $MyCarritoModel->save($MyCarrito->getArrayCopy());
    $data['resumen_metodo_envio'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.metodo_envio.phtml',['metodo_envio' =>$metodo_envio]);   
    $ObserverManager = new \Franky\Core\ObserverManager;
    $ObserverManager->dispatch('change_quote',[]);
    return $data;
}


function setNuevaDireccionCheckout($data)
{
    global $MySession;
    $MyCarritoModel = new \Ecommerce\model\CarritoModel;
    $MyCarritoEntity = getMyIdCarrito();
    

    $data2 = array();
    $data = json_decode($data,true);
    foreach($data as $node){
        $data2[$node["name"]] = $node["value"];
    }
    $MyCarritoEntity->setShippingAddres(json_encode($data2));
    $MyCarritoModel->save($MyCarritoEntity->getArrayCopy());
    $data =array("direccion_envio" => $data2,
        'resumen_envio' =>   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.envio.phtml',['direccion_envio' =>$data2])
    );

    return $data;
}


function setFacturacionCheckout($id_facturacion)
{
    global $MySession;
    $MyDireccion = new Ecommerce\model\EcommerceDireccionesFacturacionModel();
    $CarritoModel = new Ecommerce\model\CarritoModel();
    $MyCarritoEntity = getMyIdCarrito();
    $data = ['resumen_facturacion' => ""];
    $MyDireccion->setTampag(1000);
    $MyDireccion->setOrdensql("created_at ASC");
    $MyDireccion->getData($id_facturacion,$MySession->GetVar('id'));
    $total	= $MyDireccion->getTotal();

    if($total > 0)
    {
        $data = $MyDireccion->getRows();
        
        $MyCarritoEntity->setInvoiceAddres(json_encode($data));
        $data['resumen_facturacion'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.facturacion.phtml',['direccion_facturacion' =>$data]);
        
    }
    if($id_facturacion == 'no_requiere')
    {
        $MyCarritoEntity->setInvoiceAddres("");
         $data['resumen_facturacion'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.nofacturacion.phtml');

    }
    $CarritoModel->save($MyCarritoEntity->getArrayCopy());
    return $data;
}


function setNuevaFacturacionCheckout($data)
{
    global $MySession;
    $MyCarritoModel = new \Ecommerce\model\CarritoModel;
    $MyCarritoEntity = getMyIdCarrito();
    
    $data = json_decode($data,true);
    $data2 = [];
    foreach($data as $node){
        $data2[$node["name"]] = $node["value"];
    }
    $MyCarritoEntity->setInvoiceAddres(json_encode($data2));
    $MyCarritoModel->save($MyCarritoEntity->getArrayCopy());
    $data['resumen_facturacion'] =   render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/resumen.facturacion.phtml',['direccion_facturacion' =>$data2]);

    return $data;
}

function SetStatusPagoEcommerce($id,$status,$comment)
{
    global $MyAccessList;
    global $MySession;
    global $MyMessageAlert;
    
    $status = explode("_",$status);
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $pedidosModel    = new \Ecommerce\model\PedidosModel();
    $pedidosEntity   = new \Ecommerce\entity\PedidosEntity();

    $EcommercelogstatusModel    = new \Ecommerce\model\EcommerceStatusHistoryModel();
    $EcommercelogstatusEntity   = new \Ecommerce\entity\EcommerceStatusHistoryEntity();


    $respuesta = array("error" => false);

    if($MyAccessList->MeDasChancePasar("administrar_pedidos"))
    {
        $pedidosEntity->setId($Tokenizer->decode($id));  
        $pedidosEntity->setStatus($status[1]);
        $pedidosEntity->setState($status[0]);

        if($pedidosModel->save($pedidosEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_cambiar_status_pedido_success");
              $respuesta["status"] = getLabelStatusTransaccion(DATA_STORE_CONFIG["id"], $status[0], $status[1]);

              $EcommercelogstatusEntity->setState($status[0]);
              $EcommercelogstatusEntity->setStatus($status[1]);
              $EcommercelogstatusEntity->setCreatedAt(date('Y-m-d H:i:s'));
              $EcommercelogstatusEntity->setOrderId($Tokenizer->decode($id));
              $EcommercelogstatusEntity->setComment($comment);
              $EcommercelogstatusModel->save($EcommercelogstatusEntity->getArrayCopy());
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

function loadMetodosEnvio(){
    $shippingMethodsHTML = makeHTMLMetodosEnvio();
    
    $carrito = getInfoCarrito();
    if($carrito['envio_requerido'] != 1)
    {
        return array('envio_requerido' => 0,'html' => '');
    }

    $MetodoEnvioCheckoutForm = new \Ecommerce\Form\checkoutForm("frm_metodo_envio");
    $MetodoEnvioCheckoutForm->addMetodoEnvio($shippingMethodsHTML['codes']);
    $MetodoEnvioCheckoutForm->addSubmit();
    return array('envio_requerido' => $carrito['envio_requerido'],'html' => render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/frm.metodos_envio.phtml',['MetodoEnvioCheckoutForm' => $MetodoEnvioCheckoutForm,'shippingMethodsHTML' => $shippingMethodsHTML]));
}

function loadMetodosPago(){ 
    $paymentMethodsHTML = makeHTMLMetodosPago();
    
  
    $PagoCheckoutForm = new \Ecommerce\Form\checkoutForm("frm_pago");
    $PagoCheckoutForm->addMetodoPago($paymentMethodsHTML['codes']);
    $PagoCheckoutForm->addSubmit();
    
    return array('html' => render(PROJECT_DIR.'/modulos/ecommerce/diseno/checkout/frm.metodos_pago.phtml',['PagoCheckoutForm' => $PagoCheckoutForm, "paymentMethodsHTML" => $paymentMethodsHTML]));
}

function EliminarCuponesEcommerce($id,$status)
{
    $EcommercePromocionesModel             = new \Ecommerce\model\EcommercePromocionesModel();
    $EcommercePromocionesEntity             = new \Ecommerce\entity\EcommercePromocionesEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar("administrar_promociones_ecommerce"))
    {
        $EcommercePromocionesEntity->id(addslashes($Tokenizer->decode($id)));
        $EcommercePromocionesEntity->status($status);

        if($EcommercePromocionesModel->save($EcommercePromocionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
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

function EliminarStatusEcommerce($id,$status)
{
    $EcommerceStatusModel             = new \Ecommerce\model\EcommerceStatusModel();
    $EcommerceStatusEntity             = new \Ecommerce\entity\EcommerceStatusEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar("administrar_status_ecommerce"))
    {
        $EcommerceStatusEntity->setId(addslashes($Tokenizer->decode($id)));
        $EcommerceStatusEntity->setActive($status);

        if($EcommerceStatusModel->save($EcommerceStatusEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_status_error_delete");
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
    $MyCarritoModel = new CarritoModel;
    $respuesta = ['html' => ''];
    
    $valida_cupo = validaCuponEcommerce($cupon);
    if($valida_cupo['error'] == true){
        $respuesta['error'] =true;
        $respuesta['message'] = $MyMessageAlert->Message($valida_cupo['message']);
        return $respuesta;
    }
    $MyCarrito = getMyIdCarrito();
    $MyCarrito->setDiscount($valida_cupo['descuento']);
    $MyCarrito->setCoupon($cupon);
    $MyCarritoModel->save($MyCarrito->getArrayCopy());
    $ObserverManager = new \Franky\Core\ObserverManager;
    $ObserverManager->dispatch('change_quote',[]);
    $respuesta['html'] = render(PROJECT_DIR.'/modulos/ecommerce/diseno/carrito/cupon.phtml',['cupon' => $cupon]);
    
    return $respuesta;
}

function ecommerce_removeCupon()
{
    $MyCarritoModel = new CarritoModel;
    $MyCarrito = getMyIdCarrito();
    $MyCarrito->setDiscount("");
    $MyCarrito->setCoupon("");
    $MyCarritoModel->save($MyCarrito->getArrayCopy());
    $ObserverManager = new \Franky\Core\ObserverManager;
    $ObserverManager->dispatch('change_quote',[]);
    return null;
}


function getInfoTotalsCheckout()
{
    $respuesta = null;
    
    $parse_precio   =  getInfoCarrito();
    $respuesta["total"] =$parse_precio['total'];
    $respuesta["totalPlain"] =$parse_precio['totalPlain'];
    $respuesta["subtotal"] = $parse_precio['subtotal'];
    $respuesta["iva"] = $parse_precio['iva'];
    $respuesta['descuento'] = $parse_precio['discount'];
    $respuesta['descuentoPlain'] = $parse_precio['discountPlain'];
    $respuesta['monto_envio'] = $parse_precio['shippingPrice'];
    $respuesta['monto_envioPlain'] = $parse_precio['shippingPricePlain'];
    return $respuesta;
}


function placeOrder()
{
    global $MyMessageAlert;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $ObserverManager = new \Franky\Core\ObserverManager;
    $PedidosEntity = new \Ecommerce\entity\PedidosEntity(); 
    $PedidosModel = new \Ecommerce\model\PedidosModel();  

    $CarritoModel = new \Ecommerce\model\CarritoModel(); 
    $CarritoProductoModel = new \Ecommerce\model\CarritoProductoModel();  
    $ProductoPedidoEntity = new \Ecommerce\entity\ProductoPedidoEntity(); 
    $ProductoPedidoModel = new \Ecommerce\model\ProductoPedidoModel();  
    $EcommercePaymentEntity = new \Ecommerce\entity\EcommercePaymentEntity(); 
    $EcommercePaymentModel = new \Ecommerce\model\EcommercePaymentModel();  
    $EcommerceStatusHistoryEntity = new \Ecommerce\entity\EcommerceStatusHistoryEntity(); 
    $EcommerceStatusHistoryModel = new \Ecommerce\model\EcommerceStatusHistoryModel();  

    $respuesta = ["error" => true, "callback" => "", "msg" => ""];
    $ObserverManager->dispatch('change_quote',[]);
    $MyCarrito = getMyIdCarrito();
    if (empty($MyCarrito)) {
        $respuesta["msg"] = $MyMessageAlert->Message("carrito_vacio");
        $respuesta["error"] = true;
    } else {
        $paymentMethodsHTML = makeHTMLMetodosPago($MyCarrito->getPaymentMethod());
        $shippingMethodsHTML = makeHTMLMetodosEnvio($MyCarrito->getShipmentMethod());
        if(!empty($paymentMethodsHTML)) {
            $payment = call_user_func($paymentMethodsHTML["execute"]);
            if(!$payment) {
                $respuesta["msg"] = $MyMessageAlert->Message("ecommerce_payment_error");
            } else {
                
                
                $data = $MyCarrito->getArrayCopy();
                $data['quote_id'] = $MyCarrito->getId();
                $data['created_at'] = date('Y-m-d H:i:s'); 
                $data['payment_total'] = $payment['total']; 
                $data['state'] = $payment['state']; 
                $data['status'] = $payment['status']; 
                $data['order_id'] = sprintf("%010d", $MyCarrito->getId());
                unset($data['id']);
                unset($data['update_at']);

                

                $PedidosEntity->exchangeArray($data);
                if($PedidosModel->save($PedidosEntity->getArrayCopy()) == REGISTRO_SUCCESS) {

                    $orderId = $PedidosModel->getUltimoID();
                    $respuesta["callback"] = $paymentMethodsHTML["callback"]."?order=".$Tokenizer->token("create_order",$orderId);
                    $respuesta["error"] = false;
                    $productosComprados = [];
                    $CarritoProductoModel->setTampag(10000);
                    if($CarritoProductoModel->getData("", $MyCarrito->getId()) == REGISTRO_SUCCESS) {
                        while($product = $CarritoProductoModel->getRows()){
                            $product["pedido_id"] = $orderId;
                            unset($product["id"]); 
                            $product["created_at"] = date('Y-m-d');
                            $ProductoPedidoEntity->exchangeArray($product);
                            $ProductoPedidoModel->save($ProductoPedidoEntity->getArrayCopy());
                            $productosComprados[] = $ProductoPedidoEntity->getArrayCopy();

                            $ObserverManager->dispatch('product_order_save',["idProduct" => $ProductoPedidoEntity->getIdProduct(),"qty" => $ProductoPedidoEntity->getQty()]);
                        }
                    }

                    $EcommercePaymentEntity->setOrderId($orderId);
                    $EcommercePaymentEntity->setCreatedAt($payment['created_at']);
                    $EcommercePaymentEntity->setAmount($payment['total']);
                    $EcommercePaymentEntity->setPaymentMethod($MyCarrito->getPaymentMethod());
                    $EcommercePaymentEntity->setExtraData(json_encode($payment['extra_data']));
                    $EcommercePaymentModel->save($EcommercePaymentEntity->getArrayCopy());

                    $EcommerceStatusHistoryEntity->setOrderId($orderId);
                    $EcommerceStatusHistoryEntity->setState($payment['state']);
                    $EcommerceStatusHistoryEntity->setStatus($payment['status']);
                    $EcommerceStatusHistoryEntity->setComment('');
                    $EcommerceStatusHistoryEntity->setCreatedAt(date('Y-m-d H:i:s'));
                    $EcommerceStatusHistoryModel->save($EcommerceStatusHistoryEntity->getArrayCopy());

                    if($MyCarrito->getGuest() == 0) {
                        $shippingAddress = json_decode($MyCarrito->getShippingAddres(),true);
                        $billingAddress = json_decode($MyCarrito->getInvoiceAddres(),true);

                        if(!empty($shippingAddress) && isset($shippingAddress["save_address"]) && $shippingAddress["save_address"] == 1)
                        {
                            $EcommerceDireccionesModel = new \Ecommerce\model\EcommerceDireccionesModel(); 
                            $EcommerceDireccionesEntity = new \Ecommerce\entity\EcommerceDireccionesEntity(); 
                            $EcommerceDireccionesEntity->exchangeArray($shippingAddress);
                            $EcommerceDireccionesEntity->setUid($MyCarrito->getUid());
                            $EcommerceDireccionesEntity->setCreatedAt(date('Y-m-d H:i:s'));
                            $EcommerceDireccionesModel->save($EcommerceDireccionesEntity->getArrayCopy());
                        }
                        if(!empty($billingAddress) && isset($billingAddress["save_address"]) && $billingAddress["save_address"] == 1)
                        {
                            $EcommerceDireccionesFacturacionModel = new \Ecommerce\model\EcommerceDireccionesFacturacionModel(); 
                            $EcommerceDireccionesFacturacionEntity = new \Ecommerce\entity\EcommerceDireccionesFacturacionEntity(); 
                            $EcommerceDireccionesFacturacionEntity->exchangeArray($billingAddress);
                            $EcommerceDireccionesFacturacionEntity->setUid($MyCarrito->getUid());
                            $EcommerceDireccionesFacturacionEntity->setCreatedAt(date('Y-m-d H:i:s'));
                            $EcommerceDireccionesFacturacionModel->save($EcommerceDireccionesFacturacionEntity->getArrayCopy());
                        }
                    }
                    $productosHTML = render(PROJECT_DIR.'/modulos/ecommerce/diseno/email/productos.phtml',['items' =>$productosComprados]);

                    //Se envia el email
                    $campos = $PedidosEntity->getArrayCopy();
                    $direccionf = getFormatreplace(getCoreConfig("ecommerce/ventas/addressf-format"),json_decode($campos['invoice_address'],true));
                    $direccion = getFormatreplace(getCoreConfig("ecommerce/ventas/address-format"),json_decode($campos['shipping_address'],true));
                    
                    
                  
                    $campos["shipping_address"] = $direccion;
                    $campos["invoice_address"] = $direccionf;
                    $campos["payment_metho"] = $paymentMethodsHTML['name'];
                    $campos["shipping_metho"] = $shippingMethodsHTML['name'];
                    $campos["total"] = getFormatoPrecio($campos['total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
                    $campos["subtotal"] = getFormatoPrecio($campos['subtotal'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
                    $campos["tax"] = getFormatoPrecio($campos['tax'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
                    $campos["shipping_price"] = getFormatoPrecio($campos['shipping_price'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
                    $campos["discount"] = getFormatoPrecio($campos['discount'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
                    $campos["productos"] = $productosHTML;
                    $campos = array_merge($campos,$payment["data_email"]);


                    

                    $TemplateemailModel    = new \Base\model\TemplateemailModel;
                    $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
                    $TemplateemailEntity->id(
                       (!empty($paymentMethodsHTML['email_template']) ? $paymentMethodsHTML['email_template']   : getCoreConfig('ecommerce/ventas/email-sales'))
                    );
                    $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
    
                    $registro  = $TemplateemailModel->getRows();
    
                    sendEmail($campos,$registro);


                    $MyCarrito->setActive(0);
                    $CarritoModel->save($MyCarrito->getArrayCopy());

                } else {
                    $respuesta["msg"] = $MyMessageAlert->Message("ecommerce_payment_error");
                }
              
            }
    
        }

    }
    return $respuesta;
}


function ajax_setInputsConfigPromo($id,$promocion){
    global $MySession;
    
    $respuesta = ['html' => ''];
    
    $EcommercePromocionesModel             = new \Ecommerce\model\EcommercePromocionesModel();
    $EcommercePromocionesEntity             = new \Ecommerce\entity\EcommercePromocionesEntity();
    $EcommercepromocionesclassModel = new Ecommerce\model\EcommercepromocionesclassModel();
    $EcommercepromocionesclassEntity = new Ecommerce\entity\EcommercepromocionesclassEntity();
   
    
    $Form = new \Franky\Form\Form();
    
    $_data = $MySession->GetVar('data_cupon');
    if(!empty($id))
    {
        $EcommercePromocionesEntity->id($id);
        $EcommercePromocionesModel->getData($EcommercePromocionesEntity->getArrayCopy());
        $data = $EcommercePromocionesModel->getRows();	
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


function CancelOrder($id)
{
    global $MyAccessList;
    global $MyMessageAlert;
    $ObserverManager = new \Franky\Core\ObserverManager;
    $statusCode = explode("_",getCoreConfig('ecommerce/ventas/status-canceled'));
    $status = $statusCode[1];
    $state = $statusCode[0];
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $pedidosModel    = new \Ecommerce\model\PedidosModel();
    $pedidosEntity   = new \Ecommerce\entity\PedidosEntity();

    $EcommercelogstatusModel    = new \Ecommerce\model\EcommerceStatusHistoryModel();
    $EcommercelogstatusEntity   = new \Ecommerce\entity\EcommerceStatusHistoryEntity();

    $detalle_pedido = getDataOrder($Tokenizer->decode($id));

    $respuesta = array("error" => false);

    if($MyAccessList->MeDasChancePasar("administrar_pedidos") && $detalle_pedido["is_shipping"] == 0)
    {
        $pedidosEntity->setId($Tokenizer->decode($id));  
        $pedidosEntity->setStatus($status);
        $pedidosEntity->setState($state);
        $pedidosEntity->setUpdateAt(date('Y-m-d H:i:s'));
        $pedidosEntity->setIsCanceled(1);

        if($pedidosModel->save($pedidosEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
           
              $respuesta["message"] = $MyMessageAlert->Message("ecommerce_order_canceled_success");
              $respuesta["status"] = getLabelStatusTransaccion(DATA_STORE_CONFIG["id"], $state, $status);

              $EcommercelogstatusEntity->setState($state);
              $EcommercelogstatusEntity->setStatus($status);
              $EcommercelogstatusEntity->setCreatedAt(date('Y-m-d H:i:s'));
              $EcommercelogstatusEntity->setOrderId($Tokenizer->decode($id));
              $EcommercelogstatusEntity->setComment("Orden cancelada");
              $EcommercelogstatusModel->save($EcommercelogstatusEntity->getArrayCopy());

              $ObserverManager->dispatch('cancel_order',["id" => $Tokenizer->decode($id)]);
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("ecommerce_order_canceled_error");
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

/******************************** EJECUTA *************************/
$MyAjax->register("EliminarDireccionEcommerce");
$MyAjax->register("EliminarDireccionFacturacionEcommerce");
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
$MyAjax->register("loadMetodosEnvio");
$MyAjax->register("setMetodoEnvioCheckout");
$MyAjax->register("loadMetodosPago");
$MyAjax->register("ajax_setInputsConfigPromo");
$MyAjax->register("EliminarCuponesEcommerce");
$MyAjax->register("ecommerce_setCupon");
$MyAjax->register("ecommerce_removeCupon");
$MyAjax->register("getInfoTotalsCheckout");
$MyAjax->register("EliminarTiendaEcommerce");
$MyAjax->register("setCustomerDataCheckout");
$MyAjax->register("placeOrder");
$MyAjax->register("EliminarStatusEcommerce");
$MyAjax->register("CancelOrder");
?>
