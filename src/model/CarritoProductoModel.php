<?php
namespace Ecommerce\model;

class CarritoProductoModel  extends \Franky\Database\Mysql\objectOperations
{

  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_carrito_producto');
  }

    function getData($id='',$carrito='',$producto='', $data='')
    {
        $campos = array("id","quote_id","id_product","qty","data","price","tax","discount","custom_price","created_at","update_at");

        if(!empty($id))
        {
            if(is_numeric($id))
            {
                  $this->where()->addAnd('id',$id,'=');
            }
        }

        if($carrito !== "")
        {
          $this->where()->addAnd('quote_id',$carrito,'=');
        }
        if(!empty($producto))
        {
          $this->where()->addAnd('id_product',$producto,'=');
        }
        if(!empty($data))
        {
          $this->where()->addAnd('data',$data,'=');
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

    public function delete($id,$carrito)
    {
        $this->where()->addAnd('id',$id,'=');
        $this->where()->addAnd('quote_id',$carrito,'=');
        return $this->eliminarRegistro();

    }

    public function save($carrito_producto)
    {
        $carrito_producto = $this->optimizeEntity($carrito_producto);


    	if (isset($carrito_producto['id']))
    	{
          $this->where()->addAnd('id',$carrito_producto['id'],'=');
            return $this->editarRegistro($carrito_producto);
    	}
    	else {

            return $this->guardarRegistro($carrito_producto);
    	}

    }
}


?>
