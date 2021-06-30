<?php
function conekta_tarjeta()
{
    global $MyRequest;
    $MyForm =  new \Ecommerce\Form\conektaForm("card-form");

    //$MyForm->addCheckGuardar();
    $MyForm->setAtributo("action", "/ecommerce/tarjeta/confirmacion.submit.php");
    $checkoutForm = new \Ecommerce\Form\checkoutForm("frm_tarjeta_checkout");
    $checkoutForm->setAtributo("action", "/ecommerce/tarjeta/confirmacion.submit.php");
    global $MyMessageAlert;
    global $MySession;
    $respuesta = array("error" => false,"html" => "");

    if($MySession->LoggedIn())
    {

        $token = getToken("tarjeta_conekta");
        $MySession->SetVar('tarjeta_conekta',$token);
        $cards = makeHTMLCards($MySession->GetVar("id"));

        $checkoutForm->addSubmit();
        $respuesta["html"] =  render(PROJECT_DIR.'/modulos/ecommerce/diseno/conekta/conekta.tarjeta.phtml',['checkoutForm' =>$checkoutForm,'MyForm' => $MyForm,'cards' => $cards]);
         $respuesta["js"] = getJSEmbebed(render(PROJECT_DIR.'/modulos/ecommerce/diseno/conekta/validate.conekta.phtml'));
    }
    else
    {
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
        $respuesta["error"] = true;
    }

    return $respuesta;
}


function conekta_oxxo()
{

        global $MyMessageAlert;
        global $MySession;
        global $MyRequest;
        $respuesta = array("error" => false,"html" => "");

        if($MySession->LoggedIn())
        {
           $token = getToken("oxxo_pay");
           $MySession->SetVar('oxxo_pay',$token);
           $respuesta["html"] = render(PROJECT_DIR."/modulos/ecommerce/diseno/conekta/button.oxxo.phtml",['MyRequest' => $MyRequest,'token' => $token]);
        }
        else
        {
            $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
            $respuesta["error"] = true;
        }

	return $respuesta;
}
/******************************** EJECUTA *************************/
$MyAjax->register("conekta_tarjeta");
$MyAjax->register("conekta_oxxo");
?>
