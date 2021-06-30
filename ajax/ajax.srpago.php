<?php
function srpago_tarjeta()
{
    global $MyRequest;
    $MyForm =  new \Ecommerce\Form\srpagoForm("card-payment-form");

    //$MyForm->addCheckGuardar();
    $MyForm->setAtributo("action", "/ecommerce/srpago/tarjeta/confirmacion.submit.php");
    $checkoutForm = new \Ecommerce\Form\checkoutForm("frm_tarjeta_checkout");
    $checkoutForm->setAtributo("action", "/ecommerce/srpago/tarjeta/confirmacion.submit.php");
    global $MyMessageAlert;
    global $MySession;
    $respuesta = array("error" => false,"html" => "");

    if($MySession->LoggedIn())
    {



        $token = getToken("tarjeta_srpago");
        $MySession->SetVar('tarjeta_srpago',$token);
         $cards = makeHTMLCards($MySession->GetVar("id"));

         $checkoutForm->addSubmit();
        $respuesta["html"] =  render(PROJECT_DIR.'/modulos/ecommerce/diseno/srpago/srpago.tarjeta.phtml',['checkoutForm' =>$checkoutForm,'MyForm' => $MyForm,'cards' => $cards]);
        $respuesta["js"] = getJSEmbebed(render(PROJECT_DIR.'/modulos/ecommerce/diseno/srpago/validate.srpago.phtml'));
    }
    else
    {
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
        $respuesta["error"] = true;
    }

    return $respuesta;
}


function srpago_oxxo()
{

        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;
        $respuesta = array("error" => false,"html" => "");

        if($MySession->LoggedIn())
        {
           $token = getToken("establecimiento_pay");
           $MySession->SetVar('establecimiento_pay',$token);
           $respuesta["html"] = render(PROJECT_DIR."/modulos/ecommerce/diseno/srpago/button.establecimiento.phtml",['MyRequest' => $MyRequest,'token' => $token]);
        
           
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
            $respuesta["error"] = true;
        }

	return $respuesta;
}

function srpago_spei()
{

        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;
        $respuesta = array("error" => false,"html" => "");

        if($MySession->LoggedIn())
        {
           $token = getToken("spei_srpago");
           $MySession->SetVar('spei_srpago',$token);
           $respuesta["html"] = render(PROJECT_DIR."/modulos/ecommerce/diseno/srpago/button.spei.phtml",['MyRequest' => $MyRequest,'token' => $token]);
        
           
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
            $respuesta["error"] = true;
        }

	return $respuesta;
}
/******************************** EJECUTA *************************/
$MyAjax->register("srpago_tarjeta");
$MyAjax->register("srpago_oxxo");
$MyAjax->register("srpago_spei");
?>
