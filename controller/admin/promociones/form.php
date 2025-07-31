<?php
use Ecommerce\Form\CuponesPromocionesForm;
use Ecommerce\model\EcommercePromocionesModel;
use Ecommerce\entity\EcommercePromocionesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercePromocionesModel             = new EcommercePromocionesModel();
$EcommercePromocionesEntity             = new EcommercePromocionesEntity();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');

$data = $MyFlashMessage->getResponse();

$adminForm = new CuponesPromocionesForm("frmpromociones");


if(!empty($id))
{
	$EcommercePromocionesEntity->id($id);
        $EcommercePromocionesModel->getData($EcommercePromocionesEntity->getArrayCopy());
	$data = $EcommercePromocionesModel->getRows();
        $data['id'] = $Tokenizer->token('promociones', $data['id']);;
        $adminForm->addId();
        
}
$promociones = getPromocionesClass();
$adminForm->setOptionsInput('id_promocion', $promociones);
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar promociones";