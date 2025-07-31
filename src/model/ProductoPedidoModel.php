<?php
namespace Ecommerce\model;

class ProductoPedidoModel  extends \Franky\Database\Mysql\objectOperations
{


    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_pedido_producto');
    }


    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = array("id","pedido_id","id_product","qty","data","url","price","total_discount","price_discount","custom_price",
        "total","total_custom_price","created_at","sku","name","image","envio_requerido");

        foreach($data as $k => $v)
        {
            $this->where()->addAnd($k,$v,'=');
        }


        return $this->getColeccion($campos);

    }

    private function optimizeEntity($array)
    {
        foreach ($array as $k => $v )
        {
            if (!isset($v)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    public function save($ecommerce_producto_pedido)
    {
        $ecommerce_producto_pedido = $this->optimizeEntity($ecommerce_producto_pedido);


    	if (isset($ecommerce_producto_pedido['id']))
    	{
              $this->where()->addAnd('id',$ecommerce_producto_pedido['id'],'=');
            return $this->editarRegistro( $ecommerce_producto_pedido);
    	}
    	else {

            return $this->guardarRegistro( $ecommerce_producto_pedido);
    	}

    }
}
?>
