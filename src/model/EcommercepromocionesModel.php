<?php
namespace Ecommerce\model;

class EcommercepromocionesModel  extends \Franky\Database\Mysql\objectOperations
{
    private $ecommerce_promociones_class;
    
    public function __construct()
    {
        parent::__construct();
        $this->from()->addTable('ecommerce_promociones');
        $this->ecommerce_promociones_class = [];
    }
    
    public function setEcommercePromocionesClass($data)
    {
        $this->ecommerce_promociones_class = $this->optimizeEntity($data);
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["ecommerce_promociones.id","titulo","id_promocion","fecha_inicio","fecha_fin","status","data","ecommerce_promociones.createdAt",
            "ecommerce_promociones_class.nombre",
            "ecommerce_promociones_class.dataClass"];
        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                $this->where()->addAnd("ecommerce_promociones.".$k,$v,'=');
            }
        }
        if(!empty($this->ecommerce_promociones_class))
        {
            foreach( $this->ecommerce_promociones_class as $k => $v)
            {
                $this->where()->addAnd("ecommerce_promociones_class.".$k,$v,'=');
            }
        }
        $this->from()->addInner('ecommerce_promociones_class','ecommerce_promociones.id_promocion','ecommerce_promociones_class.id');

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
