<?php
namespace Ecommerce\model;

class pedidos  extends \Franky\Database\Mysql\objectOperations
{
    private $cupon;


    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('ecommerce_pedidos');
    }
    
    public function setCupon($cupon){
        $this->cupon = $cupon;
    }
    function getData($id='',$uid='',$fecha='',$status='',$referencia='',$metodo_pago='')
    {
        $campos = array("ecommerce_pedidos.id","ecommerce_pedidos.id as pedido","uid","ecommerce_pedidos.fecha","ecommerce_pedidos.status","metodo_pago","metodo_envio","monto_envio",
            "monto_compra","id_direccion_envio","id_direccion_facturacion","cupon","data_cupon",
            "referencia","fecha_pago","testigo","monto_pagado","subtotal","iva","descuento",
            "users.nombre as nombre_user");
        if(!empty($id))
        {
            if(is_numeric($id))
            {
              $this->where()->addAnd('ecommerce_pedidos.id',$id,'=');
            }
        }

        if(!empty($uid))
        {
              $this->where()->addAnd('uid',$uid,'=');
        }

        if(!empty($metodo_pago))
        {
              $this->where()->addAnd('metodo_pago',$metodo_pago,'=');
        }
        if(!empty($this->cupon))
        {
              $this->where()->addAnd('cupon',$this->cupon,'=');
        }
        if(!empty($fecha))
        {
            $this->where()->concat('AND (');
              $this->where()->addAnd('fecha',$fecha[0],'>=');
                $this->where()->addAnd('fecha',$fecha[1],'<=');
                  $this->where()->concat(')');
        }
        if(!empty($referencia))
        {
            $this->where()->addAnd('referencia','%'.$referencia.'%','like');
        }
        if(!empty($status))
        {
            $this->where()->addAnd('ecommerce_pedidos.status',$status,'=');
        }

        $this->from()->addInner('users','ecommerce_pedidos.uid','users.id');

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
