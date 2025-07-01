<?php
function _ecommerce($txt)
{
    return dgettext("ecommerce",$txt);
}

function normalizeStatusTransaccion($status){

  $array_paid = ['paid','approved','completed','processed'];
  $array_canceled = ['canceled','reversed'];
  $array_pending = ['pending','pending_payment','transfer_pending','in_progress'];
  if(in_array($status,$array_paid))
  {
      return 'paid';
  }

  if(in_array($status,$array_canceled))
  {
      return 'canceled';
  }

  if(in_array($status,$array_pending))
  {
      return 'pending';
  }
}

function getStatusTransaccion($status)
{
    switch (strtolower($status))
    {
        case "paid":
            $_status = _ecommerce("Pagado");
        break;
        case "canceled-reversal":
            $_status = _ecommerce("Cancelacion anulada");
        break;
        case "canceled":
            $_status = _ecommerce("Cancelado");
        break;
        case "denied":
            $_status = _ecommerce("Denegado");
        break;
        case "expired":
            $_status = _ecommerce("Expirado");
        break;
        case "in-progress":
            $_status = _ecommerce("En progreso");
        break;
        case "pending":
            $_status = _ecommerce("Pendiente");
        break;
        case "partially-refunded":
            $_status = _ecommerce("Reenvolso parcial");
        break;
        case "refunded":
            $_status = _ecommerce("Reenvolso total");
        break;
        case "voided":
            $_status = _ecommerce("Transaccion anulada");
        break;
        case "pago_incompleto":
            $_status = _ecommerce("Pago incompleto");
        break;
        case "request_refunded":
            $_status = _ecommerce("Solicita reenvolso");
        break;
    }


    return $_status;
}


function getMyIdCarrito()
{
    global $MySession;
    $MyCarritoCompras =  new \Ecommerce\model\CarritoModel();
    $MyCarritoEntity =  new \Ecommerce\entity\CarritoEntity();

    if($MyCarritoCompras->getData("", ($MySession->LoggedIn() ? $MySession->GetVar("id") : ""),  session_id()) == REGISTRO_SUCCESS)
    {
        $registro = $MyCarritoCompras->getRows();
        $MyCarritoEntity->exchangeArray($registro);
        return $MyCarritoEntity;
    }
    return 0;
}

function makeHTMLCards($uid = "")
{
    $CardsModel = new Ecommerce\model\CardsModel();
    $CardsEntity = new \Ecommerce\entity\CardsEntity();
    $CardsEntity->uid($uid);
    $CardsEntity->status(1);
    $CardsModel->setTampag(50);
    $CardsModel->setOrdensql("nombre ASC");
    $CardsModel->getData($CardsEntity->getArrayCopy());
    $total	= $CardsModel->getTotal();
    $cards = array();


    if($total > 0)
    {

        while($registro = $CardsModel->getRows())
        {
                $cards[$registro['id']] = $registro["nombre"].": XXXX-XXXX-XXXX-".$registro["numero"];
	}
    }


    return $cards;
}

function makeHTMLDireccion($type="envio",$uid = "")
{
    global $MySession;
    $direcciones = [];

    if (!$MySession->LoggedIn()) {
        return $direcciones;
    }
    if($type =="envio")
    {
        $MyDireccion = new Ecommerce\model\direcciones();
        $direccion = "%s: calle %s #%s, Colonia %s, municipio %s,%s C.P. %d";
    }
    if($type == "facturacion")
    {
        $MyDireccion = new Ecommerce\model\direcciones_facturacion();
        $direccion = "%s: calle %s #%s, Colonia %s, municipio %s,%s C.P. %d";
    }
    $MyDireccion->setTampag(1000);
    $MyDireccion->setOrdensql("fecha ASC");
    $MyDireccion->getData("",$uid);
    $total	= $MyDireccion->getTotal();
    


    if($total > 0)
    {

        while($registro = $MyDireccion->getRows())
        {
            if($type =="envio")
            {
                $direcciones[$registro['id']] = sprintf($direccion,$registro["nombre"],$registro["calle"],$registro["numero"],$registro["colonia"],$registro["municipio"],$registro["estado"],$registro["cp"]);
            }
            if($type =="facturacion")
            {
                $direcciones[$registro['id']] = sprintf($direccion,$registro["nombre"],$registro["calle"],$registro["numero"],$registro["colonia"],$registro["municipio"],$registro["estado"],$registro["cp"]);
            }
	}
    }


    return $direcciones;
}


function makeHTMLMetodosEnvio($id = null)
{
    $shippingMethods = ["codes" => []];
    $modulos = getModulos("DESC");

    if(!empty($modulos))
    {
        foreach($modulos as $modulo)
        {
            if(file_exists(PROJECT_DIR."/modulos/$modulo/configure/shipping.php"))
            {
                $shippingMethod = include(PROJECT_DIR."/modulos/$modulo/configure/shipping.php");
                foreach ($shippingMethod as $k => $v) {

                    if(!$v['data']['enabled']) {
                        continue;
                    } 
                    $shippingMethods["codes"][$v['code']] = $v['data']['name']." (".getFormatoPrecio($v['data']['price'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']).")";
                
                
                    $shippingMethods[$v['code']] = $v['data'];
                    if(!is_null($id) && $id == $v['code']) {
                        return $v['data'];
                    }
                }
            }
        }
    }
    if(!is_null($id)) {
        return [];
    }

    return $shippingMethods;
}


function getMetodosEnvio($id)
{
    $EcommerceenviosModel = new Ecommerce\model\EcommerceenviosModel();
    $EcommerceenviosEntity = new Ecommerce\entity\EcommerceenviosEntity();
    
    $EcommerceenviosEntity->id($id);
    $EcommerceenviosModel->getData($EcommerceenviosEntity->getArrayCopy());
    $total	= $EcommerceenviosModel->getTotal();
    
    if($total > 0)
    {
        while($registro = $EcommerceenviosModel->getRows())
        {
            if(getCoreConfig('ecommerce/'.$registro['path'].'/enabled'))
            {
                $MetodoEnvio = new $registro['dataClass'];
                
                $data = $MetodoEnvio->getData();

                return $data['price'];
            }
	    }
    }
    return false;
}

function getInfoCarrito()
{
    $MyCarritoProducto =  new \Ecommerce\model\CarritoProductoModel();
    $MyCarritoCompras =  new \Ecommerce\model\CardsModel();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
 
    $MyCarrito = getMyIdCarrito();


    $respuesta = array("qty" => 0,"subtotal" => 0,"total"=> 0,"productos"=> []);
    if($MyCarrito == 0) {
        return $respuesta;
    }
    $MyCarritoProducto->setTampag(1000);
    if($MyCarritoProducto->getData("", $MyCarrito->getId()) == REGISTRO_SUCCESS)
    {

        while($registro = $MyCarritoProducto->getRows())
        {
            $respuesta["qty"] += $registro["qty"];
            if ($registro["envio_requerido"] == 1) {
                $respuesta["envio_requerido"] = 1;
            }
            $respuesta["productos"][] = array(
                "id" => $Tokenizer->token("productos",$registro["id"]), 
                "id_producto" => $Tokenizer->token("productos",$registro["id_product"]),
                "id_producto_ori" => $registro["id_product"],
                "_id" => $registro['id'],
                "nombre" => $registro["name"],
                "url" => $registro["url"],
                "sku" => $registro["sku"],
                "precio" => getFormatoPrecio($registro["price"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                "qty" =>  $registro["qty"],
                "img" => $registro['image'],
                "total" => getFormatoPrecio($registro["total"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                "total_descuento" => getFormatoPrecio($registro["total_discount"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                "total_custom" => getFormatoPrecio($registro["total_custom_price"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                "precio_descuento" => getFormatoPrecio($registro["precio_descuento"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                "caracteristicas" => json_decode($registro['caracteristicas'],true));

        }

       
        $respuesta["total"] = getFormatoPrecio($MyCarrito->getTotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["subtotal"] = getFormatoPrecio($MyCarrito->getSubtotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["iva"] = getFormatoPrecio($MyCarrito->getTax(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["discount"] = getFormatoPrecio($MyCarrito->getDiscount(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["totalPlain"] = $MyCarrito->getTotal();
        $respuesta["subtotalPlain"] = $MyCarrito->getSubtotal();
        $respuesta["ivaPlain"] = $MyCarrito->getTax();
        $respuesta["discountPlain"] = $MyCarrito->getDiscount();
        $respuesta["shippingPrice"] = getFormatoPrecio($MyCarrito->getShippingPrice(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingSubtotal"] = getFormatoPrecio($MyCarrito->getShippingSubtotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingTax"] = getFormatoPrecio($MyCarrito->getShippingTax(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingPricePlain"] = $MyCarrito->getShippingPrice();
        $respuesta["shippingSubtotalPlain"] = $MyCarrito->getShippingSubtotal();
        $respuesta["shippingTaxPlain"] = $MyCarrito->getShippingTax();

    }

    return $respuesta;
}

function getUpdateCarrito()
{
    global $MySession;
    $MyCarritoProducto =  new \Ecommerce\model\CarritoProductoModel();
    $MyCarritoCompras =  new \Ecommerce\model\CarritoModel();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
 
    $MyCarritoEntity = getMyIdCarrito();
    $total = 0;
    $tax = 0;
    $MyCarritoProducto->setTampag(1000);
    if($MyCarritoProducto->getData("", $MyCarritoEntity->getId()) == REGISTRO_SUCCESS)
    {

        while($registro = $MyCarritoProducto->getRows())
        {
            $totalItem = $registro["total"];
            if(!empty($registro["total_discount"])){
                $totalItem = $registro["total_discount"];
            }
            if(!empty($registro["total_custom_price"])){
                $totalItem = $registro["total_custom_price"];
            }
            
            $total += $totalItem;

        }

        if($MySession->LoggedIn())
        {
            $MyCarritoEntity->setUid($MySession->GetVar("id"));
            $MyCarritoEntity->setName($MySession->GetVar("nombre"));
            $MyCarritoEntity->setEmail($MySession->GetVar("email"));

        }
        /*
            Descuentos y promociones se validaran aqui.
        */
       
       
        


        $total -= $MyCarritoEntity->getDiscount();
        $iva = DATA_STORE_CONFIG['iva'];
        $price = parsePrecio($total,$iva,1);
        
        $MyCarritoEntity->setTotal($price['total']);
        $MyCarritoEntity->setSubtotal($price['subtotal']);
        $MyCarritoEntity->setTax($price['iva']);
        $MyCarritoCompras->save($MyCarritoEntity->getArrayCopy());

        if(!empty($MyCarritoEntity->getShipmentMethod())) {
            $metodo_envio = makeHTMLMetodosEnvio($MyCarritoEntity->getShipmentMethod());
     
            $shippingData = json_decode($MyCarritoEntity->getShippingData(),true);
            if(empty($metodo_envio) || $shippingData['price'] != $metodo_envio['price']) {
                $MyCarritoEntity->setShippingMethod("");
                $MyCarritoEntity->setShippingData("");
                $MyCarritoEntity->setShippingPrice(0);
                $MyCarritoEntity->setShippingSubtotal(0);
                $MyCarritoEntity->setShippingTax(0);
            }

            $price['total'] += $MyCarritoEntity->getShippingPrice();
            $price['subtotal'] += $MyCarritoEntity->getShippingSubtotal();
            $price['iva'] += $MyCarritoEntity->getShippingTax();
            
            
            $MyCarritoEntity->setTotal($price['total']);
            $MyCarritoEntity->setSubtotal($price['subtotal']);
            $MyCarritoEntity->setTax($price['iva']);
            $MyCarritoCompras->save($MyCarritoEntity->getArrayCopy());
        }

    }
}

function setCarritoUser(){

    global $MySession;
    if(!$MySession->LoggedIn())
    {
        return false;
    }


    $MyCarritoCompras =  new \Ecommerce\model\CarritoModel();
    $MyCarritoComprasEntity =  new \Ecommerce\entity\CarritoEntity();
    if($MyCarritoCompras->getData("","", session_id()) == REGISTRO_SUCCESS)
    {


        while($registro = $MyCarritoCompras->getRows())
        {

            $MyCarritoComprasEntity->setId($registro["id"]);
            $MyCarritoComprasEntity->setUid($MySession->GetVar("id"));

            if($MyCarritoCompras->save($MyCarritoComprasEntity->getArrayCopy())==REGISTRO_SUCCESS)
            {
                getUpdateCarrito();
                return true;
            }

        }
  }
  
  return false;
}


function getPedido($id,$uid=""){

    $pedidosModel             = new \Ecommerce\model\pedidos();
    $producto_pedidoModel             = new \Ecommerce\model\producto_pedidoModel();
    $producto_pedidoEntity          = new \Ecommerce\entity\producto_pedido();
    $MyDirecciones = new \Ecommerce\model\direcciones_facturacion;


    $productos =  OBJETO_PRODUCTOS;
    $MyProducto =  new $productos();

    $result	 		= $pedidosModel->getData($id,$uid);
    $detalle_pedido = array();
    if($pedidosModel->getTotal() > 0)
    {
        $detalle_pedido = $pedidosModel->getRows();
      
        $detalle_pedido["envio"] = json_decode($detalle_pedido["id_direccion_envio"],true);
        $detalle_pedido["facturacion"] = json_decode($detalle_pedido["id_direccion_facturacion"],true);

    }
    
    if(!empty($detalle_pedido['metodo_envio']))
    {
            $EcommerceenviosModel = new Ecommerce\model\EcommerceenviosModel();
            $EcommerceenviosEntity = new Ecommerce\entity\EcommerceenviosEntity();

            $EcommerceenviosEntity->id($detalle_pedido['metodo_envio']);
            $EcommerceenviosModel->getData($EcommerceenviosEntity->getArrayCopy());

            $metodo_envio =  $EcommerceenviosModel->getRows();
            
            $detalle_pedido['metodo_envio'] = $metodo_envio;
    }
    
    

    $producto_pedidoEntity->setId_pedido($detalle_pedido['id']);
    $producto_pedidoModel->setTampag(100);
    $producto_pedidoModel->getData($producto_pedidoEntity->getArrayCopy());


    while($registro = $producto_pedidoModel->getRows())
    {

        $MyProducto->getInfoProducto($registro["id_producto"]);
        $_registro = $MyProducto->getRows();

        $detalle_pedido['productos'][] = array("id" => $registro["id_producto"],
            "qty" => $registro["qty"],
            "nombre" => $_registro["nombre"],
            "caracteristicas" => json_decode($registro["caracteristicas"],true),
            "precio" => $registro["precio"],
            "imagen" => $_registro["imagen"]
        );
    }



    return $detalle_pedido;

}

function redondeado ($numero, $decimales)
{
   $factor = pow(10, $decimales);
   return (round($numero*$factor)/$factor);
 }

function parsePrecio($precio,$iva,$incluye_iva)
{
    $piva = $iva;
  if($incluye_iva == 0)
  {
      $subtotal = $precio;
      $iva = ($subtotal*$iva)/100;
      $total = $subtotal + $iva;
  }
  else {
      $subtotal = $precio/(1+($iva/100));
      $iva = $precio-$subtotal;
      $total = $precio;
  }

  return array('subtotal' => redondeado($subtotal,2),'piva' =>$piva,'iva' =>redondeado($iva,2),'total' => redondeado($total,2),'incluye_iva' => $incluye_iva);
}


function getPromocionesClass()
{
    $EcommercepromocionesclassModel = new Ecommerce\model\EcommercepromocionesclassModel();
    $EcommercepromocionesclassEntity = new Ecommerce\entity\EcommercepromocionesclassEntity();
    
    $EcommercepromocionesclassModel->setTampag(100);
    $EcommercepromocionesclassModel->getData($EcommercepromocionesclassEntity->getArrayCopy());
    $total	= $EcommercepromocionesclassModel->getTotal();
    $promociones = [];
    if($total > 0)
    {
        while($registro = $EcommercepromocionesclassModel->getRows())
        {
            $promociones[$registro['id']] =  $registro['nombre'];
	}
    }
    return $promociones;
}

function getHTMLRenderMinicart(){
    global $MyAccessList;
    global $MySession;
    global $MyFrankyMonster;
    global $MyRequest;
    
    return render(PROJECT_DIR.'/modulos/ecommerce/diseno/carrito/widget.carrito.phtml',
            [
            'MyAccessList' => $MyAccessList,
            'MySession' => $MySession,
            'MyFrankyMonster' => $MyFrankyMonster,
            'MyRequest' => $MyRequest]);
}
        


    

function validaCuponEcommerce($cupon)
{
    global $MySession;
    
    $descuento = 0;
    
    $EcommercecuponesModel             = new Ecommerce\model\EcommercecuponesModel();
    $EcommercecuponesEntity             = new Ecommerce\entity\EcommercecuponesEntity();
    
    $EcommercecuponesEntity->codigo_promocion($cupon);
    $EcommercecuponesEntity->status(1);
    if($EcommercecuponesModel->getData($EcommercecuponesEntity->getArrayCopy()) ==REGISTRO_SUCCESS)
    {
        
        $registro = $EcommercecuponesModel->getRows();
        $id_cupon = $registro['id'];
        $numero_usos = $registro['numero_usos'];
        $numero_usos_usuario = $registro['numero_usos_usuario'];
        if($registro['fecha_inicio'] !='0000-00-00')
        {
            if(strtotime(date('Y-m-d')) < strtotime($registro['fecha_inicio']))
            {
                $respuesta['error'] =true;
                $respuesta['message'] = "ecommerce_cupon_no_exist";
                return $respuesta;
            }
        }
        if($registro['fecha_fin'] != '0000-00-00')
        {
            if(strtotime(date('Y-m-d')) > strtotime($registro['fecha_fin']))
            {
                
                $respuesta['error'] =true;
                $respuesta['message'] = "ecommerce_cupon_expired";
                return $respuesta;
            }
        }
        
        if($numero_usos > 0){
        
            $pedidosModel             = new Ecommerce\model\pedidos();

            $pedidosModel->setCupon($id_cupon);

            $pedidosModel->setTampag(1000000);
            $pedidosModel->getData();
            if($pedidosModel->getTotal() >= $numero_usos){
                $respuesta['error'] =true;
                $respuesta['message'] = "ecommerce_cupon_numero_usos";
                return $respuesta;
            }
        }
        if($numero_usos_usuario > 0){
        
            $pedidosModel             = new Ecommerce\model\pedidos();

            $pedidosModel->setCupon($id_cupon);

            $pedidosModel->setTampag(1000000);
            $pedidosModel->getData('',$MySession->GetVar('id'));
            if($pedidosModel->getTotal() >= $numero_usos_usuario){
                $respuesta['error'] =true;
                $respuesta['message'] = "ecommerce_cupon_numero_usos";
                return $respuesta;
            }
        }
        
        $EcommercepromocionesclassModel = new Ecommerce\model\EcommercepromocionesclassModel();
        $EcommercepromocionesclassEntity = new Ecommerce\entity\EcommercepromocionesclassEntity();
        
        $EcommercepromocionesclassEntity->id($registro['id_promocion']);
        $EcommercepromocionesclassModel->getData($EcommercepromocionesclassEntity->getArrayCopy());
        $_registro = $EcommercepromocionesclassModel->getRows();

        $class = new $_registro['dataClass'];

        $class->setUser($MySession->GetVar('id'));
        $class->setConfig(json_decode($registro['data'],true));
        $carrito = getCarrito();
        $class->setCarrito($carrito);
        
        $descuento = $class->getDiscount();
        if($descuento == false)
        {
            $respuesta['error'] =true;
            $respuesta['message'] = "ecommerce_cupon_no_aplica";
            return $respuesta;
        }
        
        
        $data = ['id' => $id_cupon,'cupon' => $cupon, 'descuento' => $descuento];

        $MySession->SetVar('cupon_checkout',$data);
        
        $respuesta['error'] = false;
        
        
    }
    else{
        $respuesta['error'] =true;
        $respuesta['message'] = "ecommerce_cupon_no_exist";
    }
    return $respuesta;
}





function validaPromocionEcommerce()
{
    global $MySession;
    
    $descuento = 0;
    $data =[];
    $EcommercepromocionesModel             = new Ecommerce\model\EcommercepromocionesModel();
    $EcommercepromocionesEntity             = new Ecommerce\entity\EcommercepromocionesEntity();
    

    $EcommercepromocionesEntity->status(1);

    $cupon = $MySession->GetVar('cupon_checkout');
  

    if($EcommercepromocionesModel->getData($EcommercepromocionesEntity->getArrayCopy()) ==REGISTRO_SUCCESS && $cupon == false)
    {
        
        while( $registro = $EcommercepromocionesModel->getRows()):
            $id_promo = $registro['id'];
        
            if($registro['fecha_inicio'] !='0000-00-00')
            {
                if(strtotime(date('Y-m-d')) < strtotime($registro['fecha_inicio']))
                {
                continue;
                }
            }
            if($registro['fecha_fin'] != '0000-00-00')
            {
                if(strtotime(date('Y-m-d')) > strtotime($registro['fecha_fin']))
                {
                    
                    continue;
                }
            }
            
            
            $EcommercepromocionesclassModel = new Ecommerce\model\EcommercepromocionesclassModel();
            $EcommercepromocionesclassEntity = new Ecommerce\entity\EcommercepromocionesclassEntity();
            
            $EcommercepromocionesclassEntity->id($registro['id_promocion']);
            $EcommercepromocionesclassModel->getData($EcommercepromocionesclassEntity->getArrayCopy());
            $_registro = $EcommercepromocionesclassModel->getRows();

            $class = new $_registro['dataClass'];

            $class->setUser($MySession->GetVar('id'));
            $class->setConfig(json_decode($registro['data'],true));
            $carrito = getCarrito();
            $class->setCarrito($carrito);
            
           $_descuento = $class->getDiscount();


            if($_descuento > $descuento)
            {
                $descuento = $_descuento;
                 $data = ['id' => $id_promo,'promo' => $registro['titulo'], 'descuento' => $descuento];
            }
        endwhile;
        
        $MySession->SetVar('promocion_checkout',$data);
        
        $respuesta['error'] = false;
        
        
    }
    else{
        $MySession->UnsetVar('promocion_checkout');
        $respuesta['error'] =true;
    }
    return $respuesta;
}

function getShippingPlainPrice() {
    $tipo = getCoreConfig("ecommerce/plain-price/tipo");
    $price = getCoreConfig("ecommerce/plain-price/price");
    $MyCarrito = getMyIdCarrito();
    if($tipo == "porcentaje") {
        return $MyCarrito->getTotal() * ($price/100);
    }
    return $price;
}
?>