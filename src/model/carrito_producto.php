<?php
namespace Ecommerce\model;

class carrito_producto  extends \Franky\Database\Mysql\objectOperations
{

  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_carrito_producto');
  }

    function getData($id='',$carrito='',$producto='',$caracteristicas='')
    {
        $campos = array("id","id_carrito","id_producto","qty","caracteristicas");

        if(!empty($id))
        {
            if(is_numeric($id))
            {
                  $this->where()->addAnd('id',$id,'=');
            }
        }

        if($carrito !== "")
        {
          $this->where()->addAnd('id_carrito',$carrito,'=');
        }
        if(!empty($producto))
        {
          $this->where()->addAnd('id_producto',$producto,'=');
        }
        if(!empty($caracteristicas))
        {
          $this->where()->addAnd('caracteristicas',$caracteristicas,'=');
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
        $this->where()->addAnd('id_carrito',$carrito,'=');
        return $this->eliminarRegistro();

    }

    public function save($carrito_prodcuto)
    {
        $carrito_prodcuto = $this->optimizeEntity($carrito_prodcuto);


    	if (isset($carrito_prodcuto['id']))
    	{
          $this->where()->addAnd('id',$carrito_prodcuto['id'],'=');
            return $this->editarRegistro($carrito_prodcuto);
    	}
    	else {

            return $this->guardarRegistro($carrito_prodcuto);
    	}

    }
}


?>
