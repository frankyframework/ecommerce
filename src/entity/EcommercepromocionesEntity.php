<?php
namespace Ecommerce\entity;


class EcommercepromocionesEntity
{
    private $id;
    private $titulo;
    private $id_promocion;
    private $fecha_inicio;
    private $fecha_fin;
    private $status;
    private $data;
    private $createdAt;
    private $updateAt;

    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->titulo = (isset($data["titulo"]) ? $data["titulo"] : null);
        $this->id_promocion = (isset($data["id_promocion"]) ? $data["id_promocion"] : null);
        $this->fecha_inicio = (isset($data["fecha_inicio"]) ? $data["fecha_inicio"] : null);
        $this->fecha_fin = (isset($data["fecha_fin"]) ? $data["fecha_fin"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->data = (isset($data["data"]) ? $data["data"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( "id_promocion" => array("valor" => $this->id_promocion,"required"),
             "titulo" => array("valor" => $this->titulo,"required")
        );
    }

    
    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function titulo($titulo = null){ if($titulo !== null){ $this->titulo=$titulo; }else{ return $this->titulo; } }

    public function id_promocion($id_promocion = null){ if($id_promocion !== null){ $this->id_promocion=$id_promocion; }else{ return $this->id_promocion; } }

    public function fecha_inicio($fecha_inicio = null){ if($fecha_inicio !== null){ $this->fecha_inicio=$fecha_inicio; }else{ return $this->fecha_inicio; } }

    public function fecha_fin($fecha_fin = null){ if($fecha_fin !== null){ $this->fecha_fin=$fecha_fin; }else{ return $this->fecha_fin; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function data($data = null){ if($data !== null){ $this->data=$data; }else{ return $this->data; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt !== null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

}
?>
