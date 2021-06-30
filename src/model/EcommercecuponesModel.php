<?php
namespace Ecommerce\model;

class EcommercecuponesModel  extends \Franky\Database\Mysql\objectOperations
{
    private $ecommerce_promociones_class;
    
    public function __construct()
    {
        parent::__construct();
        $this->from()->addTable('ecommerce_cupones');
        $this->ecommerce_promociones_class = [];
    }
    
    public function setEcommercePromocionesClass($data)
    {
        $this->ecommerce_promociones_class = $this->optimizeEntity($data);
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["ecommerce_cupones.id","titulo","id_promocion","fecha_inicio","fecha_fin","status","numero_usos","codigo_promocion","data","ecommerce_cupones.createdAt","numero_usos_usuario",
            "ecommerce_promociones_class.nombre",
            "ecommerce_promociones_class.dataClass"];
        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                $this->where()->addAnd("ecommerce_cupones.".$k,$v,'=');
            }
        }
        if(!empty($this->ecommerce_promociones_class))
        {
            foreach( $this->ecommerce_promociones_class as $k => $v)
            {
                $this->where()->addAnd("ecommerce_promociones_class.".$k,$v,'=');
            }
        }
        $this->from()->addInner('ecommerce_promociones_class','ecommerce_cupones.id_promocion','ecommerce_promociones_class.id');

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
    
    
     function existe($codigo,$id='')
    {
            $campos = array("id");
            $this->where()->addAnd('codigo',$codigo,'=');

            if(!empty($id))
            {
              $this->where()->addAnd('id',$id,'<>');
            }

            return $this->getColeccion($campos);
    }

}
?>
