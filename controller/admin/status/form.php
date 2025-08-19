<?php
use Ecommerce\Form\StatusForm;
use Ecommerce\model\EcommerceStatusModel;
use Ecommerce\entity\EcommerceStatusEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommerceStatusModel             = new EcommerceStatusModel();
$EcommerceStatusEntity             = new EcommerceStatusEntity();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');

$data = $MyFlashMessage->getResponse();

$adminForm = new StatusForm("frmstatus");


if(!empty($id))
{
	$EcommerceStatusEntity->setId($id);
        $EcommerceStatusModel->getData($EcommerceStatusEntity->getArrayCopy());
	$data = $EcommerceStatusModel->getRows();
        $data['id'] = $Tokenizer->token('status', $data['id']);;
       
        
}
$tiendas = getCatalogStores();
$states = getStates();
$after = getStatusTransaccion(DATA_STORE_CONFIG["id"]);
$promociones = getPromocionesClass();
$adminForm->setOptionsInput('store_id', $tiendas);
$adminForm->setOptionsInput('state', $states);
$adminForm->setOptionsInput('after', $after);
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar status";