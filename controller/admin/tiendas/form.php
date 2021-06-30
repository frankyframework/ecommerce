<?php
use Ecommerce\Form\direccionesForm;
use Ecommerce\model\EcommercetiendasModel;
use Franky\Haxor\Tokenizer;
$Tokenizer = new Tokenizer();
$EcommercetiendasModel             = new EcommercetiendasModel();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));

$callback	= $MyRequest->getRequest('callback');
$data = $MyFlashMessage->getResponse();

$adminForm = new direccionesForm("frmdirecciones");
$adminForm->setAtributo("action", "/ecommerce/admin/tiendas/submit.php");

if(!empty($id))
{
	
        $EcommercetiendasModel->getData($id);

	$data = $EcommercetiendasModel->getRows();	
        $data['id'] = $Tokenizer->token('tiendas',$data["id"]);
        $horario = json_decode($data['horario'],true);
        $adminForm->addId();
        
}
$dias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];


$adminForm->addPickupPoint();

foreach ($dias as $dia)
{
    $adminForm->addCheck($dia,($dia));
    $adminForm->addHorario($dia."_i");
    $adminForm->addHorario($dia."_f");
    
    $data[$dia] = $horario[$dia]['active'];
    $data[$dia."_i"] = $horario[$dia]['hora_i'];
    $data[$dia."_f"]= $horario[$dia]['hora_f'];

}

$adminForm->addSubmit();

$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));



$title_form = "Administrar tiendas";