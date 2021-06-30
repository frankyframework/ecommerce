<?php
namespace Ecommerce\entity;

 
class CardsEntity
{
    private $id;
    private $numero;
    private $nombre;
    private $uid;
    private $token;
    private $status;
    private $fecha;

    
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->numero = (isset($data["numero"]) ? $data["numero"] : null);
        $this->nombre = (isset($data["nombre"]) ? $data["nombre"] : null);
        $this->uid = (isset($data["uid"]) ? $data["uid"] : null);
        $this->token = (isset($data["token"]) ? $data["token"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->fecha = (isset($data["fecha"]) ? $data["fecha"] : null);

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

    public function numero($numero = null){ if($numero != null){ $this->numero=$numero; }else{ return $this->numero; } }

    public function nombre($nombre = null){ if($nombre != null){ $this->nombre=$nombre; }else{ return $this->nombre; } }

    public function uid($uid = null){ if($uid != null){ $this->uid=$uid; }else{ return $this->uid; } }

    public function token($token = null){ if($token != null){ $this->token=$token; }else{ return $this->token; } }
    
    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }
      
    public function fecha($fecha = null){ if($fecha != null){ $this->fecha=$fecha; }else{ return $this->fecha; } }


  
}
?>