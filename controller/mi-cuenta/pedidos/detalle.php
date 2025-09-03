<?php
use Ecommerce\Form\StatusPagoForm;
use Franky\Haxor\Tokenizer;
use Ecommerce\model\EcommerceStatusHistoryModel;
use Ecommerce\entity\EcommerceStatusHistoryEntity;
use Franky\Core\ObserverManager;

$Tokenizer = new Tokenizer;
$EcommercelogstatusModel    = new EcommerceStatusHistoryModel();
$EcommercelogstatusEntity   = new EcommerceStatusHistoryEntity();
$ObserverManager = new ObserverManager;
$uid = "";
$id = $Tokenizer->decode($MyRequest->getRequest('id'));
$detalle_pedido = getDataOrder($id);


$uid = $MySession->GetVar('id');


$EcommercelogstatusEntity->setOrderId($id);
$EcommercelogstatusModel->setTampag(10);
$EcommercelogstatusModel->setOrdensql('created_at DESC');
$logStatus = [];
if($EcommercelogstatusModel->getData($EcommercelogstatusEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
    while($registro = $EcommercelogstatusModel->getRows())
    {
      $registro['created_at'] = getFechaUI($registro['created_at']);
      $registro['status'] = getLabelStatusTransaccion(DATA_STORE_CONFIG["id"], $registro["state"],$registro["status"]);
      $logStatus[] = $registro;
    }
}
