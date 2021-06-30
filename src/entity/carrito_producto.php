<?php
namespace Ecommerce\entity;


 
 class carrito_producto
 {
    private $id;
    private $id_carrito;
    private $id_producto;
    private $qty;
    private $caracteristicas;

    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id               = (isset($data['id']))              ? $data['id']               : null;
        $this->id_carrito       = (isset($data['id_carrito']))      ? $data['id_carrito']       : null;
        $this->id_producto      = (isset($data['id_producto']))     ? $data['id_producto']      : null;
        $this->qty              = (isset($data['qty']))             ? $data['qty']              : null;
        $this->caracteristicas  = (isset($data['caracteristicas'])) ? $data['caracteristicas']  : null;
        
        
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        
        _ecommerce("CARRITO_ID") => array("valor" => $this->id_carrito,"required"),
        _ecommerce("Producto") => array("valor" => $this->id_producto,"required", "numeric"),
        _ecommerce("Cantidad") => array("valor" => $this->qty,"required","numeric")
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getId_carrito()
    {
        return $this->id_carrito;
    }
    
 
    public function getId_producto()
    {
        return $this->id_producto;
    }
   
    public function getQty()
    {
        return $this->qty;
    }
    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }
 
   
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setId_carrito($id_carrito)
    {
        $this->id_carrito = $id_carrito;
    }

  
    
    public function setId_producto($id_producto)
    {
        $this->id_producto = $id_producto;
    }
    
    public function setQty($qty)
    {
        $this->qty = $qty;
    }
    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;
    }
   
 }

