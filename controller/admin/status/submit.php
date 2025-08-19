<?php
use Franky\Core\validaciones; 
use Ecommerce\model\EcommerceStatusModel;
use Ecommerce\entity\EcommerceStatusEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommerceStatusModel             = new EcommerceStatusModel();
$EcommerceStatusEntity            = new EcommerceStatusEntity($MyRequest->getRequest());


$id	= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$EcommerceStatusEntity->setId($id);
$error = false;


$validaciones =  new validaciones();
$valid = $validaciones->validRules($EcommerceStatusEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}



if(!$MyAccessList->MeDasChancePasar("administrar_status_ecommerce"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if($EcommerceStatusModel->getExist($id,$EcommerceStatusEntity->getState(),$EcommerceStatusEntity->getStatus(),$EcommerceStatusEntity->getStoreId()) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("ecommerce_status_duplicado"));
    $error = true;
    die;
}


if(!$error)
{


    if(empty($id))
    {
        $EcommerceStatusEntity->setCreatedAt(date('Y-m-d H:i:s'));
        $EcommerceStatusEntity->setActive(1);
    }
    else {
        $EcommerceStatusEntity->setUpdateAt(date('Y-m-d H:i:s'));
    }
  
    $result = $EcommerceStatusModel->save($EcommerceStatusEntity->getArrayCopy());
   
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

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_LISTA_STATUS_ECOMMERCE));

      



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