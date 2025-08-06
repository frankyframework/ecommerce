<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Ecommerce\model\PedidosModel;
use Ecommerce\entity\PedidosEntity;
use Franky\Haxor\Tokenizer;
$Tokenizer = new Tokenizer;
$pedidosModel             = new PedidosModel();
$pedidosEntity             = new PedidosEntity();

$MyPaginacion = new paginacion();

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"ecommerce_pedidos.created_at"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');

$rango_inicial  = $MyRequest->getRequest("rango_inicial","");
$rango_final    = $MyRequest->getRequest("rango_final","");

$rango = array();

if(!empty($rango_inicial) && !empty($rango_final))
{
    $rango = [$rango_inicial,$rango_final];
}
if(!empty($rango_inicial) && empty($rango_final))
{
    $rango = [$rango_inicial,date('Y-m-d')];
}
if(empty($rango_inicial) && !empty($rango_final))
{
    $rango = ['1900-01-01',$rango_final];
}
$pedidosModel->setRango($rango);
$pedidosModel->setBusca($busca_b);
$pedidosEntity->setUid($MySession->GetVar('id'));
$uid = $MySession->GetVar('id');



$pedidosModel->setPage($MyPaginacion->getPage());
$pedidosModel->setTampag($MyPaginacion->getTampageDefault());
$pedidosModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());
$result	 		= $pedidosModel->getData($pedidosEntity->getArrayCopy());
$MyPaginacion->setTotal($pedidosModel->getTotal());
$lista_admin_data = array();


if($pedidosModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $pedidosModel->getRows())
    {
            $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

            $registro['total'] = getFormatoPrecio($registro['total'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);

            $lista_admin_data[] = array_merge($registro,array(
            "id" => $Tokenizer->token('pedidos',$registro["id"]),
            "_id" => $registro["id"],
            "orden" => $registro["id"],
            "callback" => $Tokenizer->token('pedidos',$MyRequest->getURI()),
            "status" => getStatusTransaccion($registro["status"]),
            "thisClass"     => $thisClass,
            "created_at" => getFechaUI($registro['created_at']),
            "status" => getStatusTransaccion($registro['status']),
            "name" => $registro['name']
            ));

            $iRow++;
    }
}

$titulo_columnas_grid = array("created_at" => _ecommerce("Fecha"),"order_id" => _ecommerce("#Orden"),"payment_method" => _ecommerce("Método de pago"), "total" =>_ecommerce("Total Compra"),"status" => _ecommerce("Estatus"));
$value_columnas_grid = array("created_at","order_id","payment_method","total","status");
$css_columnas_grid = array("created_at" => "w-xxxx-2" ,"order_id" => "w-xxxx-2" , "payment_method" => "w-xxxx-2" , "total" => "w-xxxx-3","status" => "w-xxxx-2");





$permisos_grid = "administrar_pedidos";


$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addFecha("rango_inicial");
$MyFiltrosForm->addFecha("rango_final");
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("rango_inicial", "value",$rango_inicial);
$MyFiltrosForm->setAtributoInput("rango_final", "value",$rango_final);
$MyFiltrosForm->setAtributoInput("rango_inicial", "placeholder","Desde");
$MyFiltrosForm->setAtributoInput("rango_final", "placeholder","Hasta");

?>
