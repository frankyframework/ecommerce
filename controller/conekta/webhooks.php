<?php
use Franky\Core\validaciones;
use Ecommerce\model\pedidos;
use Ecommerce\entity\pedidos as PedidosEntity;
use Ecommerce\model\producto_pedidoModel;
use Ecommerce\entity\producto_pedido as producto_pedidoEntity;
use Base\model\USERS;
use Ecommerce\model\EcommercelogstatusModel;
use Ecommerce\entity\EcommercelogstatusEntity;

$body = @file_get_contents('php://input');
$data_conketa = json_decode($body);
$referencia = (isset($data_conketa->data->object->order_id) ? $data_conketa->data->object->order_id : -1);

$USERS = new USERS();
$pedidosModel = new pedidos();
$PedidosEntity = new PedidosEntity();
$producto_pedidoModel = new producto_pedidoModel();
$producto_pedidoEntity = new producto_pedidoEntity();
$EcommercelogstatusModel    = new EcommercelogstatusModel();
$EcommercelogstatusEntity   = new EcommercelogstatusEntity();


if ($data_conketa->type == 'charge.paid'){

  
  if($pedidosModel->getData('', '','','',$referencia) == REGISTRO_SUCCESS)
  {
    $pedido = $pedidosModel->getRows();


    $producto_pedidoEntity->setId_pedido($pedido['id']);
    $producto_pedidoModel->getData($producto_pedidoEntity->getArrayCopy());
    $gran_total = 0;
    while($registro = $producto_pedidoModel->getRows())
    {
        $precio = $registro["precio"] * $registro["qty"];

        $gran_total += $precio;

        $PedidosEntity->setId($pedido['id']);



        $status_pago = normalizeStatusTransaccion($data_conketa->data->object->status);
        $total = $data_conketa->data->object->amount;

        if($status_pago == "paid")
        {
            $status_pago = ($gran_total+$pedido['monto_envio']-$pedido['descuento'] > $total ? "pago_incompleto" : $status_pago);
        }
        if($status_pago != $pedido['status'])
        {
          $PedidosEntity->setStatus($status_pago);
          $PedidosEntity->setMonto_pagado($total);
          $PedidosEntity->setMonto_compra($gran_total);
          if($pedidosModel->save($PedidosEntity->getarrayCopy()) == REGISTRO_SUCCESS)
          {
            $EcommercelogstatusEntity->status($status_pago);
            $EcommercelogstatusEntity->auto(1);
            $EcommercelogstatusEntity->id_user(0);
            $EcommercelogstatusEntity->fecha(date('Y-m-d H:i:s'));
            $EcommercelogstatusEntity->id_pedido($PedidosEntity->getId());
            $EcommercelogstatusModel->save($EcommercelogstatusEntity->getArrayCopy());

            $detalle_pedido = getPedido($PedidosEntity->getId());

            if($USERS->getData($detalle_pedido['uid'])==REGISTRO_SUCCESS)
            {


              $dataUser = $USERS->getRows();

              $productos_html = render(PROJECT_DIR.'/modulos/ecommerce/diseno/email/productos.phtml',['items' =>$detalle_pedido['productos']]);


              $campos = array("orden" => $PedidosEntity->getId(),"nombre" =>$detalle_pedido['nombre'],'productos' =>$productos_html,"email" => $dataUser['email'],
              'gran_total' => getFormatoPrecio($detalle_pedido['monto_compra']),'metodo_pago' =>$detalle_pedido['metodo_pago'],"status" => getStatusTransaccion($status_pago));

              
              $TemplateemailModel    = new \Base\model\TemplateemailModel;
              $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
              $TemplateemailEntity->id(getCoreConfig('ecommerce/ventas/email-template-cambiostatus'));
              $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
              $registro  = $TemplateemailModel->getRows();

              sendEmail($campos,$registro);

            }


          }
        }
    }
  }

}

header("HTTP/1.1 200 Ok");
?>
