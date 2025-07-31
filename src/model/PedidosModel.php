<?php
namespace Ecommerce\model;

class PedidosModel  extends \Franky\Database\Mysql\objectOperations
{
    private string $busca;
    private array $rango;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_pedidos');
    }

    public function setBusca($busca){
        $this->busca = $busca;
    }

    public function setRango($rango){
        $this->rango = $rango;
    }
    
    function getData($data=array())
    {
        $data = $this->optimizeEntity($data);
        $campos = array("id","uid","quote_id","order_id","shipping_method","payment_method","shipping_address","invoice_address",
        "name", "email", "guest","total","subtotal","tax","discount","coupon","promotion","created_at","update_at",
        "shipping_price","shipping_subtotal", "shipping_tax","shipping_data","total_items", "status", "state", "payment_total", "is_invoiced",
        "is_shipping", "is_canceled");


        if(!empty($this->busca))
        {
              $this->where()->concat('AND (');
              $this->where()->addOr('email','%'.$this->busca.'%','like');
              $this->where()->addOr('name','%'.$this->busca.'%','like');
              $this->where()->addOr('order_id','%'.$this->busca.'%','like');
              $this->where()->concat(')');
        }
        if(!empty($this->rango))
        {
              $this->where()->concat('AND (');
              $this->where()->addAnd('created_at',$this->rango[0].' 00:00:00','>=');
              $this->where()->addAnd('created_at',$this->rango[1].' 23:59:59','<=');
              $this->where()->concat(')');
        }
        foreach($data as $k => $v)
        {
            $this->where()->addAnd("ecommerce_pedidos.".$k,$v,'=');
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



    public function save($pedidos)
    {
        $pedidos = $this->optimizeEntity($pedidos);


    	if (isset($pedidos['id']))
    	{
            $this->where()->addAnd('id',$pedidos['id'],'=');
            return $this->editarRegistro($pedidos);
    	}
    	else {

            return $this->guardarRegistro( $pedidos);
    	}

    }
}


?>
