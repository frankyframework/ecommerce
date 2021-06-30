<?php
namespace Ecommerce\model;

class EcommercepromocionesclassModel  extends \Franky\Database\Mysql\objectOperations
{

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_promociones_class');
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id","nombre","dataClass","createdAt"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("ecommerce_promociones_class.".$k,$v,'=');
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

    public function save($data)
    {
        $data = $this->optimizeEntity($data);


    	if (isset($data['id']))
    	{
            $this->where()->addAnd('id',$data['id'],'=');

            return $this->editarRegistro($data);
    	}
    	else {

            return $this->guardarRegistro($data);
    	}

    }
}
?>
