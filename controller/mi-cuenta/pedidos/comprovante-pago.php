<?php
use Ecommerce\model\pedidos;
use Ecommerce\entity\pedidos as pedidosEntity;
use Franky\Filesystem\File;
use Franky\Haxor\Tokenizer;
use \Base\model\USERS;
use \Base\model\TemplateemailModel;

$Tokenizer = new Tokenizer();
$pedidosModel             = new pedidos();
$pedidosEntity             = new pedidosEntity();
$error = false;


if($MyAccessList->MeDasChancePasar("administrar_mis_pedidos"))
{
  $uid = $MySession->GetVar('id');
}
else{
  $uid = -1;
  $error = true;
  $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
}

$id = $Tokenizer->decode($MyRequest->getRequest('id'));
$detalle_pedido = getPedido($id,$uid);



$dir = $MyConfigure->getServerUploadDir()."/ecommerce/pedidos/".$uid."/$id/";
$File = new File();
$File->mkdir($dir);

if(!$error)
{
    $handle = new \Franky\Filesystem\Upload($_FILES["comprovante"]);
    if ($handle->uploaded)
    {
        if  (in_array(strtolower(pathinfo($_FILES["comprovante"]["name"], PATHINFO_EXTENSION)),array("jpg","png","gif","bmp","jpe","jpeg")))//($handle->file_is_image)
        {
            $handle->file_max_size = "2024288"; //1k(1024) x 512
            $handle->image_resize= false;
            $handle->image_ratio_fill = true;
            $handle->file_auto_rename = true;
            $handle->file_overwrite = false;
            $handle->image_background_color = '#FFFFFF';

            $handle->Process($dir);

            if ($handle->processed)
            {
                $newtestigo = $handle->file_dst_name;

            }
            else
            {
                $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("imagen_error",$handle->error));
                $error = true;
            }
        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("solo_imagen"));
            $error = true;
        }
    }
    else{
      $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("ecommerce_comprovante_vacio"));
      $error = true;
    }
}


if(!$error)
{
    $testigo = json_decode($detalle_pedido['testigo'],true);
    $testigo[] = $newtestigo;
    $pedidosEntity->setTestigo(json_encode($testigo));
    $pedidosEntity->setId($id);
    $pedidosEntity->setFecha_pago(date('Y-m-d H:i:s'));
    $result = $pedidosModel->save($pedidosEntity->getArrayCopy());

    if($result == REGISTRO_SUCCESS)
    {
        $USERS = new USERS();
        $TemplateemailModel    = new TemplateemailModel;
        $detalle_pedido = getPedido($id);

        if($USERS->getData($detalle_pedido['uid'])==REGISTRO_SUCCESS)
        {

            $dataUser = $USERS->getRows();

            $productos_html = render(PROJECT_DIR.'/modulos/ecommerce/diseno/email/productos.phtml',['items' =>$detalle_pedido['productos']]);


            $campos = array("orden" =>$id,"nombre" =>$detalle_pedido['nombre'],'productos' =>$productos_html,"email" => $dataUser['email'],
            'gran_total' => getFormatoPrecio($detalle_pedido['monto_compra'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']),'metodo_pago' =>$detalle_pedido['metodo_pago'],"status" => getStatusTransaccion($status),
            'comprovante' => makeHTMLImg( $MyRequest->link(imageResize($MyConfigure->getUploadDir()."/ecommerce/pedidos/".$detalle_pedido['uid']."/".$detalle_pedido['id']."/".$newtestigo,500,500),false,true))
);

                    
            $TemplateemailModel    = new \Base\model\TemplateemailModel;
            $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
            $TemplateemailEntity->id(getCoreConfig('ecommerce/ventas/email-comprobante-pago'));
            $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
            $registro  = $TemplateemailModel->getRows();

            sendEmail($campos,$registro);


        }

        $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("ecommerce_comprovante_success"));
        $location = (!empty($callback) ? ($callback) : $MyRequest->getReferer());

    }
    elseif($result == REGISTRO_ERROR)
    {

        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("ecommerce_comprovante_error"));

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
