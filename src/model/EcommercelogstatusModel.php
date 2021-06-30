<?php
namespace Ecommerce\model;

class EcommercelogstatusModel  extends \Franky\Database\Mysql\objectOperations
{

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_log_status');
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id","id_pedido","id_user","status","fecha","auto","info"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("ecommerce_log_status.".$k,$v,'=');
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

    public function save($ecommerce_log_status)
    {
        $ecommerce_log_status = $this->optimizeEntity($ecommerce_log_status);


    	if (isset($ecommerce_log_status['id']))
    	{
            $this->where()->addAnd('id',$ecommerce_log_status['id'],'=');

            return $this->editarRegistro($ecommerce_log_status);
    	}
    	else {

            return $this->guardarRegistro( $ecommerce_log_status);
    	}

    }
}
?>
