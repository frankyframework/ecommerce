<?php
namespace Ecommerce\model;

class EcommercePaymentModel  extends \Franky\Database\Mysql\objectOperations
{

  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_payment');
  }
    function getData($data=array())
    {
      $data = $this->optimizeEntity($data);
      $campos = array("id","order_id","payment_method","extra_data","amount","created_at","update_at");

      foreach($data as $k => $v)
      {
          $this->where()->addAnd("ecommerce_payment.".$k,$v,'=');
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
