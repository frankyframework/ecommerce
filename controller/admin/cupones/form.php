<?php
use Ecommerce\Form\CuponesPromocionesForm;
use Ecommerce\model\EcommercecuponesModel;
use Ecommerce\entity\EcommercecuponesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercecuponesModel             = new EcommercecuponesModel();
$EcommercecuponesEntity             = new EcommercecuponesEntity();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');

$data = $MyFlashMessage->getResponse();
$MySession->SetVar('data_cupon',$data);
$adminForm = new CuponesPromocionesForm("frmcupones");


if(!empty($id))
{
	$EcommercecuponesEntity->id($id);
        $EcommercecuponesModel->getData($EcommercecuponesEntity->getArrayCopy());
	$data = $EcommercecuponesModel->getRows();
        $data['id'] = $Tokenizer->token('cupones', $data['id']);;
        $adminForm->addId();
        
}
$promociones = getPromocionesClass();
$adminForm->setOptionsInput('id_promocion', $promociones);
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar cupones";