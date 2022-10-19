<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Ecommerce\model\pedidos;
use Ecommerce\model\producto_pedidoModel;
use Franky\Haxor\Tokenizer;
$Tokenizer = new Tokenizer;
$pedidosModel             = new pedidos();
$producto_pedidoModel             = new producto_pedidoModel();
$MyPaginacion = new paginacion();

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"ecommerce_pedidos.fecha"));
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

if(!$MyAccessList->MeDasChancePasar(ADMINISTRAR_PEDIDOS))
{
    $uid = $MySession->GetVar('id');
}


$pedidosModel->setPage($MyPaginacion->getPage());
$pedidosModel->setTampag($MyPaginacion->getTampageDefault());
$pedidosModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());
$result	 		= $pedidosModel->getData('', $uid,$rango);
$MyPaginacion->setTotal($pedidosModel->getTotal());
$lista_admin_data = array();


if($pedidosModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $pedidosModel->getRows())
    {
            $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

            $registro['monto_compra'] = getFormatoPrecio($registro['monto_compra']+$registro['monto_envio'],true,DATA_STORE_CONFIG['simbolo'],DATA_STORE_CONFIG['abreviatura']);

            $lista_admin_data[] = array_merge($registro,array(
              "id" => $Tokenizer->token('pedidos',$registro["id"]),
              "ecommerce_pedidos.id" => $registro["id"],
                "orden" => $registro["id"],
              "callback" => $Tokenizer->token('pedidos',$MyRequest->getURI()),
              "status" => getStatusTransaccion($registro["status"]),
            "thisClass"     => $thisClass,
            "ecommerce_pedidos.fecha" => getFechaUI($registro['fecha']),
            "ecommerce_pedidos.status" => getStatusTransaccion($registro['status']),
            "users.nombre" => $registro['nombre_user']
            ));

            $iRow++;
    }
}

$titulo_columnas_grid = array("ecommerce_pedidos.fecha" => _ecommerce("Fecha"),"ecommerce_pedidos.id" => _ecommerce("#Orden"),"metodo_pago" => _ecommerce("Método de pago"), "monto_compra" => _ecommerce("Total Compra"),"ecommerce_pedidos.status" => _ecommerce("Estatus"));
$value_columnas_grid = array("ecommerce_pedidos.fecha","ecommerce_pedidos.id","metodo_pago","monto_compra","ecommerce_pedidos.status");
$css_columnas_grid = array("ecommerce_pedidos.fecha" => "w-xxxx-2" ,"ecommerce_pedidos.id" => "w-xxxx-2" , "metodo_pago" => "w-xxxx-2" , "monto_compra" => "w-xxxx-3","ecommerce_pedidos.status" => "w-xxxx-2");

if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PEDIDOS))
{
    $titulo_columnas_grid['users.nombre'] = _ecommerce("Usuario");
		$value_columnas_grid[] ='users.nombre';
		$css_columnas_grid['monto_compra'] = 'w-xxxx-2';
		$css_columnas_grid['users.nombre'] = 'w-xxxx-1';

}



$permisos_grid = ADMINISTRAR_PEDIDOS;


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
