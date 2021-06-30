<?php
namespace Ecommerce\model;

class PreciosModel  extends \Franky\Database\Mysql\objectOperations
{


    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_precios');
    }

    function getData($precios = array())
    {
      $precios = $this->optimizeEntity($precios);
        $campos = ["id","id_moneda","id_producto","precio","iva","incluye_iva"];

        foreach($precios as $k => $v)
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

    public function save($ecommerce_precios)
    {
        $ecommerce_precios = $this->optimizeEntity($ecommerce_precios);


    	if (isset($ecommerce_precios['id']))
    	{
            $this->where()->addAnd('id',$ecommerce_precios['id'],'=');
            return $this->editarRegistro( $ecommerce_precios);
    	}
    	else {

            return $this->guardarRegistro($ecommerce_precios);
    	}

    }

    public function updateByIdProdcuto($ecommerce_precios)
    {
        $ecommerce_precios = $this->optimizeEntity($ecommerce_precios);


    	if (isset($ecommerce_precios['id_producto']))
    	{
            $this->where()->addAnd('id_producto',$ecommerce_precios['id_producto'],'=');
            return $this->editarRegistro( $ecommerce_precios);
    	}


    }
}
?>
