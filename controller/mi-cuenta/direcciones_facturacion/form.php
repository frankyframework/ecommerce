<?php
use Ecommerce\Form\direccionesForm;
use Ecommerce\model\EcommerceDireccionesFacturacionModel;

$MyDirecciones             = new EcommerceDireccionesFacturacionModel();

$id		= $MyRequest->getRequest('id');
$callback	= $MyRequest->getRequest('callback');
$data = $MyFlashMessage->getResponse();

$adminForm = new direccionesForm("frmdirecciones_facturacion");

$adminForm->setAtributo("action", "/ecommerce/mi-cuenta/direcciones_facturacion/submit.php");
if(!empty($id))
{

        $MyDirecciones->getData($id,$MySession->GetVar('id'));

	$data = $MyDirecciones->getRows();
        $adminForm->addId();

}

$adminForm->addRFC();
$adminForm->addName();
$adminForm->addSubmit();
$adminForm->setData($data);

$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "Administrar direcciones de facturacion";
