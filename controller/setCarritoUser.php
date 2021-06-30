<?php
use Ecommerce\model\carrito;
use Ecommerce\entity\carrito as carritoEntity;

$error = false;

if(!$MySession->LoggedIn())
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("no_login"));
    $error = true;
}

if($error == false)
{
    
        $MyCarritoCompras =  new carrito();
        $MyCarritoComprasEntity =  new carritoEntity();
        if($MyCarritoCompras->getData("","", session_id()) == REGISTRO_SUCCESS)
        {
            
  
            while($registro = $MyCarritoCompras->getRows())
            {
               
                $MyCarritoComprasEntity->setId($registro["id"]);
                $MyCarritoComprasEntity->setUid($MySession->GetVar("id"));
                
                $MyCarritoCompras->save($MyCarritoComprasEntity->getArrayCopy());
                
            }
        }
     
        $location =$MyRequest->url(CHECKOUT_ECOMMERCE);
        
  
}
else
{
     $location = $MyRequest->url(CARRITO_COMPRAS);
}

$MyRequest->redirect($location);
?>