<?php
use Catalog\Form\filtrosForm;
use Franky\Core\paginacion;
use Ecommerce\model\EcommerceStatusModel;
use Ecommerce\entity\EcommerceStatusEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommerceStatusModel             = new EcommerceStatusModel();
$EcommerceStatusEntity            = new EcommerceStatusEntity();
$MyPaginacion = new paginacion();



$store_b	= $MyRequest->getRequest('store_b');

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"ecommerce_status.created_at"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));			
$busca_b	= $MyRequest->getRequest('busca_b');	
	

$EcommerceStatusModel->setPage($MyPaginacion->getPage());
$EcommerceStatusModel->setTampag($MyPaginacion->getTampageDefault());
$EcommerceStatusModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());


//$EcommerceStatusEntity->setactive(1);
if(!empty($store_b)) {
    $EcommerceStatusEntity->setStoreId($store_b);
}
$EcommerceStatusModel->setBusca($busca_b);


$result	 		= $EcommerceStatusModel->getData($EcommerceStatusEntity->getArrayCopy());
$MyPaginacion->setTotal($EcommerceStatusModel->getTotal());
$lista_admin_data = array();


if($EcommerceStatusModel->getTotal() > 0)
{
	
	$iRow = 0;	

	while($registro = $EcommerceStatusModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
                
                
                $lista_admin_data[] = array_merge($registro,array(
                    "created_at"        => getFechaUI($registro["created_at"]),
                    "thisClass"     => $thisClass,
                    "nuevo_estado"  =>($registro["active"] == 1 ?"desactivar" : "activar"),
                    "id" => $Tokenizer->token('status',$registro["id"]),
                    "callback" => $Tokenizer->token('status',$MyRequest->getURI()),
                ));
                
                $iRow++;
        }
        
}

$title_grid = _ecommerce("Administrar status");
$class_grid = "cont_status";
$error_grid = _ecommerce("No hay status registrados");
$deleteFunction = "EliminarStatusEcommerce";
$frm_constante_link = ADMIN_FRM_STATUS_ECOMMERCE;

$titulo_columnas_grid = array("created_at" => _ecommerce("Fecha"),"state" => _ecommerce("Status base"),"status" => _ecommerce("Status"),"label" => _ecommerce("Etiqueta"));
$value_columnas_grid = array("created_at" ,"state","status","label");
$css_columnas_grid = array("created_at" => 'w-xxxx-2',"state" => "w-xxxx-2" ,"status" => "w-xxxx-2" , "label" => "w-xxxx-3");

$permisos_grid = "administrar_status_ecommerce";
$tiendas = getCatalogStores();	
$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addStore();
$MyFiltrosForm->addSubmit();
$MyFiltrosForm->setOptionsInput("store_b", $tiendas);
$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
$MyFiltrosForm->setAtributoInput("store_b", "value",$store_b);
?>