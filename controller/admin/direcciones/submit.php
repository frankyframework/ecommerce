<?php
use Franky\Core\validaciones; 
use Ecommerce\model\direcciones;
use Ecommerce\entity\direcciones as direccionesEntity;

$MyDirecciones             = new direcciones();
$MyDireccionesEntity       = new direccionesEntity($MyRequest->getRequest());
$id = $MyDireccionesEntity->getId(); 
$callback = $MyRequest->getRequest("callback");
$error = false;


$validaciones =  new validaciones();
$valid = $validaciones->validRules($MyDireccionesEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}
if(!empty($id))
{
    if($MyDirecciones->getData($MyDireccionesEntity->getId(),$MySession->GetVar('id')) != REGISTRO_SUCCESS)
    {
        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
        $error = true;
    }
}


if(!$MyAccessList->MeDasChancePasar(ADMINISTRAR_DIRECCIONES_ECOMMERCE))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}



if(!$error)
{


    if(empty($id))
    {
        $MyDireccionesEntity->setFecha(date('Y-m-d H:i:s'));
        $MyDireccionesEntity->setUid($MySession->GetVar('id'));
        $MyDireccionesEntity->setStatus(1);
    }
    
    $result = $MyDirecciones->save($MyDireccionesEntity->getArrayCopy());
   
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

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_LISTA_DIRECCIONES_ECOMMERCE));

      



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