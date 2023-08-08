<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Ecommerce\model\EcommercetiendasModel;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$EcommercetiendasModel             = new EcommercetiendasModel();
$MyPaginacion = new paginacion();

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"fecha"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));			
$busca_b	= $MyRequest->getRequest('busca_b');	
	


$EcommercetiendasModel->setPage($MyPaginacion->getPage());
$EcommercetiendasModel->setTampag($MyPaginacion->getTampageDefault());
$EcommercetiendasModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());
$result	 		= $EcommercetiendasModel->getData('',"",$busca_b);
$MyPaginacion->setTotal($EcommercetiendasModel->getTotal());
$lista_admin_data = array();


if($EcommercetiendasModel->getTotal() > 0)
{
	
	$iRow = 0;	

	while($registro = $EcommercetiendasModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
                
                $direccion = "%s: calle %s #%s, Colonia %s, municipio %s,%s C.P. %d";
                $lista_admin_data[] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "nuevo_estado"  =>($registro["status"] == 1 ?"desactivar" : "activar"),
                    "id" => $Tokenizer->token('tiendas',$registro["id"]),
                    "callback" => $Tokenizer->token('tiendas',$MyRequest->getURI()),
                    "calle" => sprintf($direccion,$registro["nombre"],$registro["calle"],$registro["numero"],$registro["colonia"],$registro["municipio"],$registro["estado"],$registro["cp"])
                ));
                
                $iRow++;
        }
        
        
}


$MyFrankyMonster->setPHPFile(getVista("admin/template/grid.phtml"));
$title_grid = _ecommerce("Tiendas");
$class_grid = "cont_tiendas";
$error_grid = _ecommerce("No hay tiendas registradas");
$deleteFunction = "EliminarTiendaEcommerce";
$frm_constante_link = FRM_TIENDAS_ECOMMERCE;
$titulo_columnas_grid = array("nombre" => _ecommerce("Nombre"),"calle" => _ecommerce("Direccion"));
$value_columnas_grid = array("nombre","calle");


$css_columnas_grid = array("nombre" => 'w-xxxx-3',"calle" => "w-xxxx-6" );



$permisos_grid = "administrar_tiendas_ecommerce";

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>