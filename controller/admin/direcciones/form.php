<?php
use Ecommerce\Form\direccionesForm;
use Ecommerce\model\direcciones;

$MyDirecciones             = new direcciones();

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

$adminForm->addOtroTelefono();
$adminForm->addEntrecalles();
$adminForm->addInstrucciones();
$adminForm->addSubmit();
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar direcciones";