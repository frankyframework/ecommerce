<?php
namespace Ecommerce\entity;


class EcommerceenviosEntity
{
    private $id;
    private $nombre;
    private $dataClass;
    private $createdAt;
    private $path;


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
        $this->path = (isset($data["path"]) ? $data["path"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array();
    }

    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function nombre($nombre = null){ if($nombre != null){ $this->nombre=$nombre; }else{ return $this->nombre; } }

    public function dataClass($dataClass = null){ if($dataClass != null){ $this->dataClass=$dataClass; }else{ return $this->dataClass; } }

    public function path($path = null){ if($path !== null){ $this->path=$path; }else{ return $this->path; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

}
?>
