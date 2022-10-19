<?php
use Ecommerce\model\pedidos;
use Ecommerce\entity\pedidos as PedidosEntity;
use Ecommerce\model\producto_pedidoModel;
use Ecommerce\entity\producto_pedido as producto_pedidoEntity;
use Base\model\USERS;
use Ecommerce\model\EcommercelogstatusModel;
use Ecommerce\entity\EcommercelogstatusEntity;
use \Base\model\CoreConfigModel;
use \Base\entity\CoreConfigEntity;

$CoreConfigModel      = new CoreConfigModel();
$CoreConfigEntity     = new CoreConfigEntity();

$body = @file_get_contents('php://input');
$data_openpay = json_decode($body,true);
$referencia = (isset($data_openpay['transaction']['order_id']) ? $data_openpay['transaction']['order_id'] : -1);

$USERS = new USERS();
$pedidosModel = new pedidos();
$PedidosEntity = new PedidosEntity();
$producto_pedidoModel = new producto_pedidoModel();
$producto_pedidoEntity = new producto_pedidoEntity();
$EcommercelogstatusModel    = new EcommercelogstatusModel();
$EcommercelogstatusEntity   = new EcommercelogstatusEntity();

if ($data_openpay['type']== 'verification'){ 
   $CoreConfigEntity->path('ecommerce/openpay/codewebhook');
   $CoreConfigEntity->value($data_openpay['verification_code']);
                                
   $result = $CoreConfigModel->updateByPath($CoreConfigEntity->getArrayCopy());
}
if ($data_openpay['type'] == 'charge.succeeded'){

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



        $status_pago = normalizeStatusTransaccion($data_openpay['transaction']['status']);
        $total = $data_openpay['transaction']['amount'];

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
              'gran_total' => getFormatoPrecio($detalle_pedido['monto_compra'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),'metodo_pago' =>$detalle_pedido['metodo_pago'],"status" => getStatusTransaccion($status_pago));

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
