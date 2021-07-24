<?php
use Ecommerce\Form\ComprovantePagoForm;
use Ecommerce\Form\StatusPagoForm;
use Franky\Haxor\Tokenizer;
use Ecommerce\model\EcommercelogstatusModel;
use Ecommerce\entity\EcommercelogstatusEntity;

$Tokenizer = new Tokenizer;
$EcommercelogstatusModel    = new EcommercelogstatusModel();
$EcommercelogstatusEntity   = new EcommercelogstatusEntity();

$uid = "";
$id = $Tokenizer->decode($MyRequest->getRequest('id'));
$detalle_pedido = getPedido($id,$uid);



$StatusPagoForm = new StatusPagoForm('frmStatus');
$StatusPagoForm->setAtributoInput('id', 'value', $MyRequest->getRequest('id'));
$StatusPagoForm->setAtributoInput('status', 'value', $detalle_pedido['status']);

$EcommercelogstatusEntity->id_pedido($id);
$EcommercelogstatusModel->setTampag(10);
$EcommercelogstatusModel->setOrdensql('fecha DESC');
$logStatus = [];
if($EcommercelogstatusModel->getData($EcommercelogstatusEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
    while($registro = $EcommercelogstatusModel->getRows())
    {
      $registro['info'] = json_decode($registro['info'],true);
      $registro['fecha'] = getFechaUI($registro['fecha']);
      $registro['status'] = getStatusTransaccion($registro['status']);
      $logStatus[] = $registro;
    }
}
