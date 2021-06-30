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

$productos =  OBJETO_PRODUCTOS;
$MyProducto =  new $productos();

$paymentID = $MyRequest->getRequest('paymentID');
$payerID = $MyRequest->getRequest('payerID');
$token = $MyRequest->getRequest('token');

$referencia = array("paymentID" => $paymentID,"payerID" =>$payerID,"token" => $token);

$MyCarritoCompras =  new carrito();
$MyPedido = new pedidos_model();
$MyPedidoEntity = new pedidos();
$MyPedidoProducto = new producto_pedidoModel();
$MyPedidoProductoEntity = new producto_pedido();

$cupon = $MySession->GetVar('cupon_checkout');
if($cupon != false)
{
    $valida_cupo = validaCuponEcommerce($cupon['cupon']);
    if($valida_cupo['error'] == true){
        ecommerce_removeCupon();
    }
}
    
if(!empty($paymentID) && !empty($payerID) && !empty($token))
{
    $productos_comprados = getCarrito();
}

$paypalCheck=paypalCheck($paymentID);

$status_pago = normalizeStatusTransaccion($paypalCheck->state);
$total = $paypalCheck->transactions[0]->amount->total;
$currency = $paypalCheck->transactions[0]->amount->currency;
//$subtotal = $paypalCheck->transactions[0]->amount->details->subtotal;
$recipient_name = $paypalCheck->transactions[0]->item_list->shipping_address->recipient_name;


if($status_pago == "pending" || $status_pago == "paid")
{

    if($MyPedido->getData("",$MySession->GetVar('id'),"","",json_encode($referencia)) == REGISTRO_SUCCESS)
    {
        $registro = $MyPedido->getRows();
        $MyPedidoEntity->setId($registro["id"]);
        $MyPedidoEntity->setStatus($status_pago);
        $MyPedido->save($MyPedidoEntity->getArrayCopy());
    }
    else
    {

        if(!empty($productos_comprados['productos']))
        {

            $data = $MySession->GetVar('checkout');
            
            if($status_pago == "paid")
            {
                $status_pago = ($productos_comprados['gran_total'] -$productos_comprados['descuento'] + $data['monto_envio'] > $total ? "pago_incompleto" : $status_pago);
            }

            

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
                    $direccion_envio = $data['direcciones_envio'];



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
            $MyPedidoEntity->setMetodo_pago("paypal");
            $MyPedidoEntity->setMetodo_envio($data['id_metodo_envio']);
            $MyPedidoEntity->setMonto_compra($productos_comprados['gran_total']);
            $MyPedidoEntity->setSubtotal($productos_comprados['subtotal']);
            $MyPedidoEntity->setIva($productos_comprados['iva_total']);
            $MyPedidoEntity->setMonto_pagado($total);
            $MyPedidoEntity->setMonto_envio($data['monto_envio']);
            $MyPedidoEntity->setDescuento($productos_comprados['descuento']);
            $MyPedidoEntity->setData_cupon(json_encode($cupon));
            $MyPedidoEntity->setCupon($cupon['id']);
            $MyPedidoEntity->setReferencia(json_encode($referencia));

            if($MyPedido->save($MyPedidoEntity->getArrayCopy()) == REGISTRO_SUCCESS)
            {
                $pedido = $MyPedido->getUltimoID();
                $detalle_pedido = getPedido($pedido,$MySession->GetVar('id'));
                
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
                    'subtotal' => getFormatoPrecio($productos_comprados['subtotal']),
                    'iva' => getFormatoPrecio($productos_comprados['iva_total']),
                    'envio' => getFormatoPrecio($data['monto_envio']),
                    'descuento' => getFormatoPrecio($productos_comprados['descuento']),
                    'gran_total' => getFormatoPrecio($productos_comprados['gran_total']+$data['monto_envio']-$productos_comprados['descuento']),
                    'metodo_pago' =>'PayPal',
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
        else{
             $MyRequest->redirect();
        }
    }
    $MyMetatag->setTitulo("Confirmacion de pedido paypal");
    $MyMetatag->setDescripcion("Confirmacion de pedido paypal");
    $MyMetatag->setkeywords("");

}

?>
