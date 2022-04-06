<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Ecommerce\model\EcommercecuponesModel;
use Ecommerce\entity\EcommercecuponesEntity;
use Franky\Haxor\Tokenizer;


$Tokenizer = new Tokenizer();
$EcommercecuponesModel             = new EcommercecuponesModel();
$EcommercecuponesEntity            = new EcommercecuponesEntity();
$MyPaginacion = new paginacion();





$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"ecommerce_cupones.createdAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));			
$busca_b	= $MyRequest->getRequest('busca_b');	
	

$alias = ['createdAt' => "ecommerce_cupones.createdAt"];
if(isset($alias[$MyRequest->getRequest('por')]))
{
    $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

$EcommercecuponesModel->setPage($MyPaginacion->getPage());
$EcommercecuponesModel->setTampag($MyPaginacion->getTampageDefault());
$EcommercecuponesModel->setOrdensql($orden." ".$MyPaginacion->getOrden());


if(getCoreConfig('ecommerce/cupones/showdelete') == 0){
    $EcommercecuponesEntity->status(1);
}


$result	 		= $EcommercecuponesModel->getData($EcommercecuponesEntity->getArrayCopy());
$MyPaginacion->setTotal($EcommercecuponesModel->getTotal());
$lista_admin_data = array();


if($EcommercecuponesModel->getTotal() > 0)
{
	
	$iRow = 0;	

	while($registro = $EcommercecuponesModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
                
                
                $lista_admin_data[] = array_merge($registro,array(
                    "createdAt"        => getFechaUI($registro["createdAt"]),
                    "thisClass"     => $thisClass,
                    "nuevo_estado"  =>($registro["status"] == 1 ?"desactivar" : "activar"),
                    "id" => $Tokenizer->token('cupones',$registro["id"]),
                    "callback" => $Tokenizer->token('cupones',$MyRequest->getURI()),
                ));
                
                $iRow++;
        }
        
}

$title_grid = _ecommerce("Administración de cupones");
$class_grid = "cont_cupones";
$error_grid = _ecommerce("No hay cupones registrados");
$deleteFunction = "EliminarCuponesEcommerce";
$frm_constante_link = ADMIN_FRM_CUPONES_ECOMMERCE;

$titulo_columnas_grid = array("createdAt" => _ecommerce("Fecha"),"titulo" => _ecommerce("Titulo"),"codigo_promocion" => _ecommerce("Cupon"),"nombre" => _ecommerce("Tipo"));
$value_columnas_grid = array("createdAt" ,"titulo","codigo_promocion","nombre");
$css_columnas_grid = array("createdAt" => 'w-xxxx-1',"titulo" => "w-xxxx-4" ,"codigo_promocion" => "w-xxxx-2" , "nombre" => "w-xxxx-3");

$permisos_grid = ADMINISTRAR_CUPONES_ECOMMERCE;

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>