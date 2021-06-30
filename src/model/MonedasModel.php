<?php
namespace Ecommerce\model;

class MonedasModel  extends \Franky\Database\Mysql\objectOperations
{


    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_monedas');
    }
    function getData($monedas = array())
    {
        $monedas = $this->optimizeEntity($monedas);
        $campos = ["id","nombre"];

        foreach($monedas as $k => $v)
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

    public function save($ecommerce_monedas)
    {
        $ecommerce_monedas = $this->optimizeEntity($ecommerce_monedas);


    	if (isset($ecommerce_monedas['id']))
    	{
            $this->where()->addAnd('id',$ecommerce_monedas['id'],'=');
            return $this->editarRegistro($ecommerce_monedas);
    	}
    	else {

            return $this->guardarRegistro($ecommerce_monedas);
    	}

    }
}
?>
