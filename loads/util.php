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

    if($MyCarritoCompras->getData("", ($MySession->LoggedIn() ? $MySession->GetVar("id") : ""),  session_id(),'1') == REGISTRO_SUCCESS)
    {
        $registro = $MyCarritoCompras->getRows();
        $MyCarritoEntity->exchangeArray($registro);
        return $MyCarritoEntity;
    }
    return 0;
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
        $MyDireccion = new Ecommerce\model\EcommerceDireccionesModel();
        $direccion = getCoreConfig("ecommerce/ventas/address-format");;
    }
    if($type == "facturacion")
    {
        $MyDireccion = new Ecommerce\model\EcommerceDireccionesFacturacionModel();
        $direccion = getCoreConfig("ecommerce/ventas/addressf-format");;
    }
    $MyDireccion->setTampag(1000);
    $MyDireccion->setOrdensql("created_at ASC");
    $MyDireccion->getData("",$uid);
    $total	= $MyDireccion->getTotal();
    


    if($total > 0)
    {

        while($registro = $MyDireccion->getRows())
        {
            if($type =="envio")
            {
                $direcciones[$registro['id']] = sprintf($direccion,$registro["calle"],$registro["numero"],$registro["colonia"],$registro["municipio"],$registro["estado"],$registro["cp"]);
            }
            if($type =="facturacion")
            {
                $direcciones[$registro['id']] = sprintf($direccion,$registro["nombre"],$registro["rfc"],$registro["calle"],$registro["numero"],$registro["colonia"],$registro["municipio"],$registro["estado"],$registro["cp"]);
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

function makeHTMLMetodosPago($id = null)
{
    $paymentMethods = ["codes" => []];
    $modulos = getModulos("DESC");

    if(!empty($modulos))
    {
        foreach($modulos as $modulo)
        {
            if(file_exists(PROJECT_DIR."/modulos/$modulo/configure/payment.php"))
            {
                $paymentMethods = include(PROJECT_DIR."/modulos/$modulo/configure/payment.php");
                foreach ($paymentMethods as $k => $v) {

                    if(!$v['data']['enabled']) {
                        continue;
                    } 
                    $paymentMethods["codes"][$v['code']] = $v['data']['name'];
                
                
                    $paymentMethods[$v['code']] = $v['data'];
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

    return $paymentMethods;
}


function getInfoCarrito()
{
    $MyCarritoProducto =  new \Ecommerce\model\CarritoProductoModel();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
 
    $MyCarrito = getMyIdCarrito();


    $respuesta = array("qty" => 0,"subtotal" => 0,"total"=> 0,"productos"=> []);
    if(empty($MyCarrito)) {
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
                "precio_descuento" => getFormatoPrecio($registro["price_discount"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                "caracteristicas" => json_decode($registro['data'],true));

        }

        $respuesta["totalItems"] = getFormatoPrecio($MyCarrito->getTotalItems(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["total"] = getFormatoPrecio($MyCarrito->getTotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["subtotal"] = getFormatoPrecio($MyCarrito->getSubtotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["iva"] = getFormatoPrecio($MyCarrito->getTax(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["discount"] = getFormatoPrecio($MyCarrito->getDiscount(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["totalItemsPlain"] = $MyCarrito->getTotalItems();
        $respuesta["totalPlain"] = $MyCarrito->getTotal();
        $respuesta["subtotalPlain"] = $MyCarrito->getSubtotal();
        $respuesta["ivaPlain"] = $MyCarrito->getTax();
        $respuesta["discountPlain"] = $MyCarrito->getDiscount();
        $respuesta["shippingPrice"] = getFormatoPrecio($MyCarrito->getShippingPrice(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingSubtotal"] = getFormatoPrecio($MyCarrito->getShippingSubtotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingTax"] = getFormatoPrecio($MyCarrito->getShippingTax(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingPricePlain"] = (int)$MyCarrito->getShippingPrice();
        $respuesta["shippingSubtotalPlain"] = $MyCarrito->getShippingSubtotal();
        $respuesta["shippingTaxPlain"] = $MyCarrito->getShippingTax();
        $respuesta["cupon"] = $MyCarrito->getCoupon();

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
            $MyCarritoEntity->setGuest(0);
        } else {
            $MyCarritoEntity->setGuest(1);
        }

        $MyCarritoEntity->setTotalItems($total);
        $MyCarritoCompras->save($MyCarritoEntity->getArrayCopy());
        $cupon =  $MyCarritoEntity->getCoupon();
        if(!empty($cupon)){
            $valida_cupo = validaCuponEcommerce($cupon);
            if($valida_cupo['error'] == true){
                $MyCarritoEntity->setCoupon("");
                $MyCarritoEntity->setDiscount(0);
            }
        }

        $iva = DATA_STORE_CONFIG['iva'];

       
        $price = parsePrecio($total,$iva,1);
        
        $MyCarritoEntity->setTotal($price['total']);
        $MyCarritoEntity->setSubtotal($price['subtotal']);
        $MyCarritoEntity->setTax($price['iva']);

        $priceD = parsePrecio($MyCarritoEntity->getTotal() - $MyCarritoEntity->getDiscount(),$iva,1);
        $MyCarritoEntity->setTotal($priceD['total']);

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

            $priceD['total'] += $MyCarritoEntity->getShippingPrice();
            
            
            $MyCarritoEntity->setTotal($priceD['total']);
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
    $EcommercePromocionesModel             = new Ecommerce\model\EcommercePromocionesModel();
    $EcommercePromocionesEntity             = new Ecommerce\entity\EcommercePromocionesEntity();
    $carrito = getMyIdCarrito();

    $EcommercePromocionesEntity->codigo_promocion($cupon);
    $EcommercePromocionesEntity->status(1);
    if($EcommercePromocionesModel->getData($EcommercePromocionesEntity->getArrayCopy()) ==REGISTRO_SUCCESS)
    {
        
        $registro = $EcommercePromocionesModel->getRows();
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
        
            $pedidosModel             = new Ecommerce\model\PedidosModel();
            $pedidosEntity             = new Ecommerce\entity\PedidosEntity();

            $pedidosEntity->setCoupon($cupon);
            

            $pedidosModel->setTampag($numero_usos +1 );
            $pedidosModel->getData($pedidosEntity->getArrayCopy());
            if($pedidosModel->getTotal() >= $numero_usos){
                $respuesta['error'] =true;
                $respuesta['message'] = "ecommerce_cupon_numero_usos";
                return $respuesta;
            }
        }
        
        if($numero_usos_usuario > 0){
        
            if(!$MySession->LoggedIn()) {
                $respuesta['error'] =true;
                $respuesta['message'] = "ecommerce_cupon_no_register";
                return $respuesta;
            }
            $pedidosModel             = new Ecommerce\model\PedidosModel();
            $pedidosEntity             = new Ecommerce\entity\PedidosEntity();
            $pedidosEntity->setCoupon($cupon);
            $pedidosEntity->setEmail($carrito->getEmail());
            $pedidosModel->setTampag($numero_usos_usuario +1 );
            $pedidosModel->getData($pedidosEntity->getArrayCopy());
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
        
        $class->setTotalProducts($carrito->getTotalItems());
        
        $descuento = $class->getDiscount();
        if($descuento == false)
        {
            $respuesta['error'] =true;
            $respuesta['message'] = "ecommerce_cupon_no_aplica";
            return $respuesta;
        }
        
        
        $respuesta = ['cupon' => $cupon, 'descuento' => $descuento];
        
        $respuesta['error'] = false;
        
        
    }
    else{
        $respuesta['error'] =true;
        $respuesta['message'] = "ecommerce_cupon_no_exist";
    }
    return $respuesta;
}

function getDataOrder($orderId)
{
    $PedidosModel =  new \Ecommerce\model\PedidosModel();
    $PedidosEntity =  new \Ecommerce\entity\PedidosEntity();
    $ProductoPedidoModel =  new \Ecommerce\model\ProductoPedidoModel();
    $ProductoPedidoEntity =  new \Ecommerce\entity\ProductoPedidoEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $PedidosEntity->setOrderId($orderId);


    $respuesta = array("qty" => 0,"subtotal" => 0,"total"=> 0,"productos"=> []);

    $PedidosModel->setTampag(1);
    if($PedidosModel->getData($PedidosEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {

        $PedidosEntity->exchangeArray($PedidosModel->getRows());
        $ProductoPedidoEntity->getPedidoId($PedidosEntity->getId());

        if($ProductoPedidoModel->getData($ProductoPedidoEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            while($_registro = $ProductoPedidoModel->getRows()) {

                $respuesta["qty"] += $_registro["qty"];
                if ($_registro["envio_requerido"] == 1) {
                    $respuesta["envio_requerido"] = 1;
                }
                $respuesta["productos"][] = array(
                    "id" => $Tokenizer->token("productos",$_registro["id"]), 
                    "id_producto" => $Tokenizer->token("productos",$_registro["id_product"]),
                    "id_producto_ori" => $_registro["id_product"],
                    "_id" => $_registro['id'],
                    "nombre" => $_registro["name"],
                    "url" => $_registro["url"],
                    "sku" => $_registro["sku"],
                    "precio" => getFormatoPrecio($_registro["price"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                    "qty" =>  $_registro["qty"],
                    "img" => $_registro['image'],
                    "total" => getFormatoPrecio($_registro["total"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                    "total_descuento" => getFormatoPrecio($_registro["total_discount"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                    "total_custom" => getFormatoPrecio($_registro["total_custom_price"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                    "precio_descuento" => getFormatoPrecio($_registro["price_discount"],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),
                    "caracteristicas" => json_decode($_registro['data'],true));

            }
        }

        $respuesta["totalItems"] = getFormatoPrecio($PedidosEntity->getTotalItems(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["total"] = getFormatoPrecio($PedidosEntity->getTotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["subtotal"] = getFormatoPrecio($PedidosEntity->getSubtotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["iva"] = getFormatoPrecio($PedidosEntity->getTax(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["discount"] = getFormatoPrecio($PedidosEntity->getDiscount(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["totalItemsPlain"] = $PedidosEntity->getTotalItems();
        $respuesta["totalPlain"] = $PedidosEntity->getTotal();
        $respuesta["subtotalPlain"] = $PedidosEntity->getSubtotal();
        $respuesta["ivaPlain"] = $PedidosEntity->getTax();
        $respuesta["discountPlain"] = $PedidosEntity->getDiscount();
        $respuesta["shippingPrice"] = getFormatoPrecio($PedidosEntity->getShippingPrice(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingSubtotal"] = getFormatoPrecio($PedidosEntity->getShippingSubtotal(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingTax"] = getFormatoPrecio($PedidosEntity->getShippingTax(),true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);
        $respuesta["shippingPricePlain"] = (int)$PedidosEntity->getShippingPrice();
        $respuesta["shippingSubtotalPlain"] = $PedidosEntity->getShippingSubtotal();
        $respuesta["shippingTaxPlain"] = $PedidosEntity->getShippingTax();
        $respuesta["cupon"] = $PedidosEntity->getCoupon();
        $respuesta["status"] = $PedidosEntity->getStatus();
        $respuesta["state"] = $PedidosEntity->getState();
        $respuesta["order_id"] = $PedidosEntity->getOrderId();
    }

    return $respuesta;
}

function getShippingPlainPrice() {
    $tipo = getCoreConfig("ecommerce/plain-price/tipo");
    $price = getCoreConfig("ecommerce/plain-price/price");
    $MyCarrito = getMyIdCarrito();
    if($tipo == "porcentaje") {
        return $MyCarrito->getTotalItems() * ($price/100);
    }
    return $price;
}

function getFreePayEnabled(){
    if(getCoreConfig("ecommerce/free_pay/enabled") == 0) {
        return false;
    }
    $MyCarrito = getMyIdCarrito();
    if($MyCarrito->getTotal() > 0)
    {
        return false;
    }
    return true;
}

function placeOrderFreePay(){
    if(getCoreConfig("ecommerce/free_pay/enabled") == 0) {
        return false;
    }
    $MyCarrito = getMyIdCarrito();
    if($MyCarrito->getTotal() > 0)
    {
        return false;
    }

    return [
        "total" => 0,
        "extra_data" => [],
        "payment_method" => "free_pay",
        "created_at" => date('Y-m-d H:i:s'),
        "status" => "processing",
        "state" => "processing",
        "data_email" => []
    ];
}

function getCallbakFreePay(){
    global $MyRequest;
    return $MyRequest->url(ECOMMERCE_SUCCESS_PAGE);
}
?>