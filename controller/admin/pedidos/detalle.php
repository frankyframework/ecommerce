<?php
use Ecommerce\Form\StatusPagoForm;
use Franky\Haxor\Tokenizer;
use Ecommerce\model\EcommerceStatusHistoryModel;
use Ecommerce\entity\EcommerceStatusHistoryEntity;

$Tokenizer = new Tokenizer;
$EcommercelogstatusModel    = new EcommerceStatusHistoryModel();
$EcommercelogstatusEntity   = new EcommerceStatusHistoryEntity();

$uid = "";
$id = $Tokenizer->decode($MyRequest->getRequest('id'));
$detalle_pedido = getDataOrder($id);


;
$StatusPagoForm = new StatusPagoForm('frmStatus');
$StatusPagoForm->setAtributoInput('order_id', 'value', $MyRequest->getRequest('id'));
$StatusPagoForm->setOptionsInput('status', getStatusTransaccion(DATA_STORE_CONFIG["id"], $detalle_pedido['state']."_".$detalle_pedido['status']));
$StatusPagoForm->setAtributoInput('status', 'value',  $detalle_pedido['state']."_".$detalle_pedido['status']);

$EcommercelogstatusEntity->setOrderId($id);
$EcommercelogstatusModel->setTampag(10);
$EcommercelogstatusModel->setOrdensql('created_at DESC');
$logStatus = [];
if($EcommercelogstatusModel->getData($EcommercelogstatusEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
    while($registro = $EcommercelogstatusModel->getRows())
    {
      $registro['comment'] = $registro['comment'];
      $registro['created_at'] = getFechaUI($registro['created_at']);
      $registro['status'] = getLabelStatusTransaccion(DATA_STORE_CONFIG["id"], $registro["state"],$registro["status"]);
      $logStatus[] = $registro;
    }
}
