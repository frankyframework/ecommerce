<?php
use Franky\Core\validaciones; 
use Ecommerce\model\EcommercePromocionesModel;
use Ecommerce\entity\EcommercePromocionesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercePromocionesModel             = new EcommercePromocionesModel();
$EcommercePromocionesEntity            = new EcommercePromocionesEntity($MyRequest->getRequest());


$id	= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$EcommercePromocionesEntity->id($id);
$error = false;

if($MyRequest->getRequest('fecha_inicio') == '')
{
    $EcommercePromocionesEntity->fecha_inicio('0000-00-00');
}
if($MyRequest->getRequest('fecha_fin') == '')
{
    $EcommercePromocionesEntity->fecha_fin('0000-00-00');
}


$data = $MyRequest->getRequest();
unset($data['id']);
unset($data['titulo']);
unset($data['codigo_promocion']);
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
unset($data['numero_usos']);
unset($data['numero_usos_usuario']);

$validaciones =  new validaciones();
$valid = $validaciones->validRules($EcommercePromocionesEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}



if(!$MyAccessList->MeDasChancePasar("administrar_promociones_ecommerce"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if($EcommercePromocionesModel->existe($EcommercePromocionesEntity->codigo_promocion(),$EcommercePromocionesEntity->id()) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("ecommerce_codigo_cupon_duplicado"));
    $error = true;
}


if(!$error)
{


    if(empty($id))
    {
        $EcommercePromocionesEntity->createdAt(date('Y-m-d H:i:s'));
        $EcommercePromocionesEntity->status(1);
    }
    else {
        $EcommercePromocionesEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $EcommercePromocionesEntity->data(json_encode($data));
    $result = $EcommercePromocionesModel->save($EcommercePromocionesEntity->getArrayCopy());
   
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