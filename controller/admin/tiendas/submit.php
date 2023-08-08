<?php
use Franky\Core\validaciones; 
use Ecommerce\model\EcommercetiendasModel;
use Ecommerce\entity\EcommercetiendasEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercetiendasModel             = new EcommercetiendasModel();
$EcommercetiendasEntity       = new EcommercetiendasEntity($MyRequest->getRequest());

$id	= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$EcommercetiendasEntity->setId($id);

$dias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
$horario = [];

foreach($dias  as $dia)
{
    $horario[$dia] = ['active' => $MyRequest->getRequest($dia,0),'hora_i' => $MyRequest->getRequest($dia.'_i'),'hora_f' =>$MyRequest->getRequest($dia.'_f')];
}
   
$error = false;

$EcommercetiendasEntity->setHorario(json_encode($horario));
$validaciones =  new validaciones();
$valid = $validaciones->validRules($EcommercetiendasEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar("administrar_tiendas_ecommerce"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}



if(!$error)
{


    if(empty($id))
    {
        $EcommercetiendasEntity->setFecha(date('Y-m-d H:i:s'));
        $EcommercetiendasEntity->setStatus(1);
    }
    
    $result = $EcommercetiendasModel->save($EcommercetiendasEntity->getArrayCopy());
   
    if($result == REGISTRO_SUCCESS)
    {

       
        if(empty($id))
        {
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("guardar_generico_success"));
        }
        else 
        {
             $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        }

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_LISTA_TIENDAS_ECOMMERCE));

      



    }
    elseif($result == REGISTRO_ERROR)
    {
        
        if(empty($id))
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("editar_generico_error"));
        }
        $location = $MyRequest->getReferer();
    }
    else
    {
        $MyFlashMessage->setMsg("error",$result);
        $location = $MyRequest->getReferer();
    }
}
else
{
    $location = $MyRequest->getReferer();
}


$MyRequest->redirect($location);
?>