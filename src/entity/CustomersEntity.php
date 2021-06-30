<?php
namespace Ecommerce\entity;


class CustomersEntity
{
    private $id;
    private $id_user;
    private $token;
    private $id_categoria;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->id_user = (isset($data["id_user"]) ? $data["id_user"] : null);
        $this->token = (isset($data["token"]) ? $data["token"] : null);
        $this->id_categoria = (isset($data["id_categoria"]) ? $data["id_categoria"] : null);

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

    public function id_user($id_user = null){ if($id_user != null){ $this->id_user=$id_user; }else{ return $this->id_user; } }

    public function id_categoria($id_categoria = null){ if($id_categoria != null){ $this->id_categoria=$id_categoria; }else{ return $this->id_categoria; } }

    public function token($token = null){ if($token != null){ $this->token=$token; }else{ return $this->token; } }
}
?>
