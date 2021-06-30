<?php
function openpay_tarjeta()
{
    global $MyRequest;
    $MyForm =  new \Ecommerce\Form\openpayForm("card-form");

    //$MyForm->addCheckGuardar();
    $MyForm->setAtributo("action", "/ecommerce/openpay/tarjeta/confirmacion.submit.php");
    $checkoutForm = new \Ecommerce\Form\checkoutForm("frm_tarjeta_checkout");
    $checkoutForm->setAtributo("action", "/ecommerce/openpay/tarjeta/confirmacion.submit.php");
    global $MyMessageAlert;
    global $MySession;
    $respuesta = array("error" => false,"html" => "");

    if($MySession->LoggedIn())
    {



        $token = getToken("tarjeta_openpay");
        $MySession->SetVar('tarjeta_openpay',$token);
         $cards = makeHTMLCards($MySession->GetVar("id"));

         $checkoutForm->addSubmit();
        $respuesta["html"] =  render(PROJECT_DIR.'/modulos/ecommerce/diseno/openpay/openpay.tarjeta.phtml',['checkoutForm' =>$checkoutForm,'MyForm' => $MyForm,'cards' => $cards]);
        $respuesta["js"] = getJSEmbebed(render(PROJECT_DIR.'/modulos/ecommerce/diseno/openpay/validate.openpay.phtml'));
    }
    else
    {
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
        $respuesta["error"] = true;
    }

    return $respuesta;
}


function openpay_establecimiento()
{

        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;
        $respuesta = array("error" => false,"html" => "");

        if($MySession->LoggedIn())
        {
           $token = getToken("establecimiento_pay");
           $MySession->SetVar('establecimiento_pay',$token);
           $respuesta["html"] = render(PROJECT_DIR."/modulos/ecommerce/diseno/openpay/button.establecimiento.phtml",['MyRequest' => $MyRequest,'token' => $token]);
        
           
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
            $respuesta["error"] = true;
        }

	return $respuesta;
}
/******************************** EJECUTA *************************/
$MyAjax->register("openpay_tarjeta");
$MyAjax->register("openpay_establecimiento");
?>
