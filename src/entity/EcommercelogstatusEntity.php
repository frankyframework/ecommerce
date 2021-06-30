<?php
namespace Ecommerce\entity;


class EcommercelogstatusEntity
{
    private $id;
    private $id_pedido;
    private $id_user;
    private $status;
    private $fecha;
    private $auto;
    private $info;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->id_pedido = (isset($data["id_pedido"]) ? $data["id_pedido"] : null);
        $this->id_user = (isset($data["id_user"]) ? $data["id_user"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->fecha = (isset($data["fehca"]) ? $data["fehca"] : null);
        $this->auto = (isset($data["auto"]) ? $data["auto"] : null);
        $this->info = (isset($data["info"]) ? $data["info"] : null);
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

    public function id_pedido($id_pedido = null){ if($id_pedido != null){ $this->id_pedido=$id_pedido; }else{ return $this->id_pedido; } }

    public function id_user($id_user = null){ if($id_user != null){ $this->id_user=$id_user; }else{ return $this->id_user; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function fecha($fecha = null){ if($fecha != null){ $this->fecha=$fecha; }else{ return $this->fecha; } }

    public function auto($auto = null){ if($auto !== null){ $this->auto=$auto; }else{ return $this->auto; } }

    public function info($info = null){ if($info != null){ $this->info=$info; }else{ return $this->info; } }

}
?>
