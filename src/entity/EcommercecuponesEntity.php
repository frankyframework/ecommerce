<?php
namespace Ecommerce\entity;


class EcommercecuponesEntity
{
    private $id;
    private $titulo;
    private $id_promocion;
    private $fecha_inicio;
    private $fecha_fin;
    private $status;
    private $numero_usos;
    private $codigo_promocion;
    private $data;
    private $createdAt;
    private $numero_usos_usuario;


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
        $this->numero_usos = (isset($data["numero_usos"]) ? $data["numero_usos"] : null);
        $this->codigo_promocion = (isset($data["codigo_promocion"]) ? $data["codigo_promocion"] : null);
        $this->data = (isset($data["data"]) ? $data["data"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->numero_usos_usuario = (isset($data["numero_usos_usuario"]) ? $data["numero_usos_usuario"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( "id_promocion" => array("valor" => $this->id_promocion,"required"),
            "codigo_promocion" => array("valor" => $this->codigo_promocion,"required"),
             "titulo" => array("valor" => $this->titulo,"required")
        );
    }

    
    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function titulo($titulo = null){ if($titulo !== null){ $this->titulo=$titulo; }else{ return $this->titulo; } }

    public function id_promocion($id_promocion = null){ if($id_promocion !== null){ $this->id_promocion=$id_promocion; }else{ return $this->id_promocion; } }

    public function fecha_inicio($fecha_inicio = null){ if($fecha_inicio !== null){ $this->fecha_inicio=$fecha_inicio; }else{ return $this->fecha_inicio; } }

    public function fecha_fin($fecha_fin = null){ if($fecha_fin !== null){ $this->fecha_fin=$fecha_fin; }else{ return $this->fecha_fin; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function numero_usos($numero_usos = null){ if($numero_usos !== null){ $this->numero_usos=$numero_usos; }else{ return $this->numero_usos; } }

    public function codigo_promocion($codigo_promocion = null){ if($codigo_promocion !== null){ $this->codigo_promocion=$codigo_promocion; }else{ return $this->codigo_promocion; } }

    public function data($data = null){ if($data !== null){ $this->data=$data; }else{ return $this->data; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function numero_usos_usuario($numero_usos_usuario = null){ if($numero_usos_usuario !== null){ $this->numero_usos_usuario=$numero_usos_usuario; }else{ return $this->numero_usos_usuario; } }

}
?>
