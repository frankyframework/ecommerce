<?php
namespace Ecommerce\model;

class carrito  extends \Franky\Database\Mysql\objectOperations
{

  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_carrito');
  }
    function getData($id='',$uid='',$cookie_id='',$invoice='')
    {
        $campos = array("id","uid","id_envio","id_facturacion","id_cupon","id_pago","invoice");

        if(!empty($id))
        {
            if(is_numeric($id))
            {
                 $this->where()->addAnd('id',$id,'=');
            }


        }

        if(!empty($uid))
        {
          $this->where()->addAnd('uid',$uid,'=');
        }

        if(!empty($cookie_id))
        {
          $this->where()->addAnd('cookie_id',$cookie_id,'=');
        }

        if(!empty($invoice))
        {
          $this->where()->addAnd('invoice',$invoice,'=');
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

    public function delete($id)
    {
        $this->where()->addAnd('id',$id,'=');
            return $this->eliminarRegistro();

    }


    public function save($carrito)
    {
        $carrito = $this->optimizeEntity($carrito);


    	if (isset($carrito['id']))
    	{
          $this->where()->addAnd('id',$carrito['id'],'=');
            return $this->editarRegistro($carrito);
    	}
    	else {

            return $this->guardarRegistro($carrito);
    	}

    }
}


?>
