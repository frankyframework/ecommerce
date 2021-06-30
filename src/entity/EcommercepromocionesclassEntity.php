<?php
namespace Ecommerce\entity;


class EcommercepromocionesclassEntity
{
    private $id;
    private $nombre;
    private $dataClass;
    private $createdAt;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->nombre = (isset($data["nombre"]) ? $data["nombre"] : null);
        $this->dataClass = (isset($data["dataClass"]) ? $data["dataClass"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( 
            "nombre" => array("valor" => $this->nombre,"required"),
            "dataClass" => array("valor" => $this->dataClass,"required")
            );
    }

    
    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function nombre($nombre = null){ if($nombre !== null){ $this->nombre=$nombre; }else{ return $this->nombre; } }

    public function dataClass($dataClass = null){ if($dataClass !== null){ $this->dataClass=$dataClass; }else{ return $this->dataClass; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

}
?>
