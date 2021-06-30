<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Ecommerce\model\EcommercepromocionesModel;
use Ecommerce\entity\EcommercepromocionesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercepromocionesModel             = new EcommercepromocionesModel();
$EcommercepromocionesEntity            = new EcommercepromocionesEntity();
$MyPaginacion = new paginacion();


$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"ecommerce_promociones.createdAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));			
$busca_b	= $MyRequest->getRequest('busca_b');	
	

$alias = ['createdAt' => "ecommerce_promociones.createdAt"];
if(isset($alias[$MyRequest->getRequest('por')]))
{
    $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

$EcommercepromocionesModel->setPage($MyPaginacion->getPage());
$EcommercepromocionesModel->setTampag($MyPaginacion->getTampageDefault());
$EcommercepromocionesModel->setOrdensql($orden." ".$MyPaginacion->getOrden());


if(getCoreConfig('ecommerce/promociones/showdelete') == 0){
    $EcommercepromocionesEntity->status(1);
}


$result	 		= $EcommercepromocionesModel->getData($EcommercepromocionesEntity->getArrayCopy());
$MyPaginacion->setTotal($EcommercepromocionesModel->getTotal());
$lista_admin_data = array();


if($EcommercepromocionesModel->getTotal() > 0)
{
	
	$iRow = 0;	

	while($registro = $EcommercepromocionesModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
                
                
                $lista_admin_data[] = array_merge($registro,array(
                    "createdAt"        => getFechaUI($registro["createdAt"]),
                    "thisClass"     => $thisClass,
                    "nuevo_estado"  =>($registro["status"] == 1 ?"desactivar" : "activar"),
                    "id" => $Tokenizer->token('promociones',$registro["id"]),
                    "callback" => $Tokenizer->token('promociones',$MyRequest->getURI()),
                ));
                
                $iRow++;
        }
        
}

$title_grid = "Administración de promociones";
$class_grid = "cont_promociones";
$error_grid = "No hay promociones registradas";
$deleteFunction = "EliminarPromocionesEcommerce";
$frm_constante_link = ADMIN_FRM_PROMOCIONES_ECOMMERCE;

$titulo_columnas_grid = array("createdAt" => "Fecha","titulo" => "Titulo","nombre" => "Tipo");
$value_columnas_grid = array("createdAt" ,"titulo","nombre");
$css_columnas_grid = array("createdAt" => 'w-xxxx-1',"titulo" => "w-xxxx-4" , "nombre" => "w-xxxx-3");

$permisos_grid = ADMINISTRAR_PROMOCIONES_ECOMMERCE;

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>