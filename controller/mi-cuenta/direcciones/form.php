<?php
use Ecommerce\Form\direccionesForm;
use Ecommerce\model\EcommerceDireccionesModel;

$MyDirecciones             = new EcommerceDireccionesModel();

$id		= $MyRequest->getRequest('id');
$callback	= $MyRequest->getRequest('callback');
$data = $MyFlashMessage->getResponse();

$adminForm = new direccionesForm("frmdirecciones");


if(!empty($id))
{
	
        $MyDirecciones->getData($id,$MySession->GetVar('id'));

	$data = $MyDirecciones->getRows();	
        $adminForm->addId();
        
}

$adminForm->addEntrecalles();
$adminForm->addSubmit();
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar direcciones";