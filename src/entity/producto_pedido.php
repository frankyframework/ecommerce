<?php
namespace Ecommerce\entity;


 
 class producto_pedido
 {
    private $id;
    private $id_pedido;
    private $id_producto;
    private $qty;
    private $caracteristicas;
    private $precio;

    
    public function __construct($data = null)
    {
        if (null != $data) 
        {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id               = (isset($data['id']))                  ? $data['id']               : null;
        $this->id_pedido        = (isset($data['id_pedido']))           ? $data['id_pedido']        : null;
        $this->id_producto      = (isset($data['id_producto']))         ? $data['id_producto']      : null;
        $this->qty              = (isset($data['qty']))                 ? $data['qty']              : null;
        $this->caracteristicas              = (isset($data['caracteristicas']))                 ? $data['caracteristicas']              : null;
        $this->precio  = (isset($data['precio']))     ? $data['precio']  : null;
       
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getId_pedido()
    {
        return $this->id_pedido;
    }
    
    public function getId_producto()
    {
        return $this->id_producto;
    }
    
    public function getQty()
    {
        return $this->qty;
    }
   
    public function getPrecio()
    {
        return $this->precio;
    }
    

    
    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }
 
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setId_pedido($id_pedido)
    {
        $this->id_pedido = $id_pedido;
    }
    
    public function setId_producto($id_producto)
    {
        $this->id_producto = $id_producto;
    }
    
    public function setQty($qty)
    {
        $this->qty = $qty;
    }
   
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    

    
    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;
    }


 }

