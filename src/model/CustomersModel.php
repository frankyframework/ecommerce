<?php
namespace Ecommerce\model;

class CustomersModel  extends \Franky\Database\Mysql\objectOperations
{

  public function __construct()
  {
    parent::__construct();
    
  }

  public function setTable($table)
  {
    $this->from()->addTable($table);
  }

    function getData($customer=array())
    {
        $customer = $this->optimizeEntity($customer);
        $campos = ["id","id_user","token"];

        foreach($customer as $k => $v)
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

    public function save($ecommerce_customers)
    {
        $ecommerce_customers = $this->optimizeEntity($ecommerce_customers);


    	if (isset($ecommerce_customers['id']))
    	{
            $this->where()->addAnd('id',$ecommerce_customers['id'],'=');
            return $this->editarRegistro( $ecommerce_customers);
    	}
    	else {

            return $this->guardarRegistro( $ecommerce_customers);
    	}

    }

    public function delete($ecommerce_customers)
    {
        $ecommerce_customers = $this->optimizeEntity($ecommerce_customers);


    	if (isset($ecommerce_customers['id']))
    	{
            $this->where()->addAnd('id',$ecommerce_customers['id'],'=');
            return $this->eliminarRegistro();
    	}
    	else {

        foreach($ecommerce_customers as $k => $v)
        {
            $this->where()->addAnd($k,$v,'=');
        }

        return $this->eliminarRegistro();
    	}

    }
}
?>
