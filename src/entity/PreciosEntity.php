<?php
namespace Ecommerce\entity;

 
class PreciosEntity
{
    private $id;
    private $id_moneda;
    private $id_producto;
    private $precio;
    private $iva;
    private $incluye_iva;

    
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->id_moneda = (isset($data["id_moneda"]) ? $data["id_moneda"] : null);
        $this->id_producto = (isset($data["id_producto"]) ? $data["id_producto"] : null);
        $this->precio = (isset($data["precio"]) ? $data["precio"] : null);
        $this->iva = (isset($data["iva"]) ? $data["iva"] : null);
        $this->incluye_iva = (isset($data["incluye_iva"]) ? $data["incluye_iva"] : null);

    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
         return array(
          "Precio" => array("valor" => $this->precio,"required"),
          "IVA" => array("valor" => $this->iva,"required","numeric")
          );
    }

    
    
    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function id_moneda($id_moneda = null){ if($id_moneda != null){ $this->id_moneda=$id_moneda; }else{ return $this->id_moneda; } }

    public function id_producto($id_producto = null){ if($id_producto != null){ $this->id_producto=$id_producto; }else{ return $this->id_producto; } }

    public function precio($precio = null){ if($precio != null){ $this->precio=$precio; }else{ return $this->precio; } }

    public function iva($iva = null){ if($iva != null){ $this->iva=$iva; }else{ return $this->iva; } }

    public function incluye_iva($incluye_iva = null){ if($incluye_iva != null){ $this->incluye_iva=$incluye_iva; }else{ return $this->incluye_iva; } }


  
}
?>