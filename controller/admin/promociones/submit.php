<?php
use Franky\Core\validaciones; 
use Ecommerce\model\EcommercepromocionesModel;
use Ecommerce\entity\EcommercepromocionesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercepromocionesModel             = new EcommercepromocionesModel();
$EcommercepromocionesEntity            = new EcommercepromocionesEntity($MyRequest->getRequest());


$id	= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$EcommercepromocionesEntity->id($id);
$error = false;

if($MyRequest->getRequest('fecha_inicio') == '')
{
    $EcommercepromocionesEntity->fecha_inicio('0000-00-00');
}
if($MyRequest->getRequest('fecha_fin') == '')
{
    $EcommercepromocionesEntity->fecha_fin('0000-00-00');
}


$data = $MyRequest->getRequest();
unset($data['id']);
unset($data['titulo']);
unset($data['fecha_inicio']);
unset($data['fecha_inicio_dia']);
unset($data['fecha_inicio_mes']);
unset($data['fecha_inicio_ano']);
unset($data['fecha_fin']);
unset($data['fecha_fin_dia']);
unset($data['fecha_fin_mes']);
unset($data['fecha_fin_ano']);
unset($data['id_promocion']);
unset($data['guardar']);

$validaciones =  new validaciones();
$valid = $validaciones->validRules($EcommercepromocionesEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}



if(!$MyAccessList->MeDasChancePasar(ADMINISTRAR_PROMOCIONES_ECOMMERCE))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if(!$error)
{


    if(empty($id))
    {
        $EcommercepromocionesEntity->createdAt(date('Y-m-d H:i:s'));
        $EcommercepromocionesEntity->status(1);
    }
    else{
        $EcommercepromocionesEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $EcommercepromocionesEntity->data(json_encode($data));
    $result = $EcommercepromocionesModel->save($EcommercepromocionesEntity->getArrayCopy());
   
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

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_LISTA_PROMOCIONES_ECOMMERCE));

      



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