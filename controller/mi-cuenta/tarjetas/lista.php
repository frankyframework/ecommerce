<?php
use Ecommerce\model\CardsModel;
use Ecommerce\entity\CardsEntity;

$CardsModel             = new CardsModel();
$CardsEntity    = new CardsEntity();


$busca_b	= $MyRequest->getRequest('busca_b');	
	
$CardsEntity->uid($MySession->GetVar('id'));
$CardsEntity->status(1);
$CardsModel->setPage(1);
$CardsModel->setTampag(50);
$CardsModel->setOrdensql("fecha DESC");
$result = $CardsModel->getData($CardsEntity->getArrayCopy(),$busca_b);

$lista_admin_data = array();


if($CardsModel->getTotal() > 0)
{
    $iRow = 0;	

    while($registro = $CardsModel->getRows())
    {
        $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

        $registro["numero"] = "XXXX-XXXX-XXXX-".$registro["numero"];
        $lista_admin_data[] = array_merge($registro,array(
        "thisClass"     => $thisClass,

        ));

        $iRow++;
    }  
}
$title_grid = "Mis tarjetas";
$error_grid = "No hay tarjetas registradas";
$deleteFunction = "EliminarTarjetaEcommerce";
$frm_constante_link = FRM_TARJETAS_ECOMMERCE;
$value_columnas_grid = array("nombre","numero");
$permisos_grid = "administrar_tarjetas_ecommerce";
?>