<?php
use Ecommerce\Form\PromocionesForm;
use Ecommerce\model\EcommercepromocionesModel;
use Ecommerce\entity\EcommercepromocionesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercepromocionesModel             = new EcommercepromocionesModel();
$EcommercepromocionesEntity             = new EcommercepromocionesEntity();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');

$data = $MyFlashMessage->getResponse();
$MySession->SetVar('data_cupon',$data);
$adminForm = new PromocionesForm("frmpromocion");


if(!empty($id))
{
	$EcommercepromocionesEntity->id($id);
        $EcommercepromocionesModel->getData($EcommercepromocionesEntity->getArrayCopy());
	$data = $EcommercepromocionesModel->getRows();
        $data['id'] = $Tokenizer->token('promociones', $data['id']);;
        $adminForm->addId();     
}
$promociones = getPromocionesClass();
$adminForm->setOptionsInput('id_promocion', $promociones);
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar promociones";