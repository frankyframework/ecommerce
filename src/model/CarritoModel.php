<?php
namespace Ecommerce\model;

class CarritoModel  extends \Franky\Database\Mysql\objectOperations
{

  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_carrito');
  }
    function getData($id='',$uid='',$cookie_id='', $active='1')
    {
        $campos = array("id","uid","cookie_id","shipping_method","payment_method","shipping_address","invoice_address",
        "name", "email", "guest","total","subtotal","tax","discount","coupon","promotion","created_at","update_at",
      "shipping_price","shipping_subtotal", "shipping_tax","shipping_data","total_items");

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

        if(!empty($active))
        {
          $this->where()->addAnd('active',$active,'=');
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
