<?php
namespace Ecommerce\entity;

class EcommerceStatusEntity
{
    private $id;
    private $store_id;
    private $state;
    private $status;
    private $label;
    private $created_at;
    private $update_at;
    private $active;
    private $after;
   
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id']))                  ? $data['id']     : null;
        $this->store_id     = (isset($data['store_id']))                 ? $data['store_id']  : null;
        $this->state        = (isset($data['state']))      ? $data['state']       : null;
        $this->status       = (isset($data['status']))    ? $data['status']     : null;
        $this->label        = (isset($data['label']))    ? $data['label']     : null;
        $this->created_at   = (isset($data['created_at']))          ? $data['created_at']    : null;
        $this->update_at    = (isset($data['update_at']))          ? $data['update_at']    : null;
        $this->active    = (isset($data['active']))          ? $data['active']    : null;
        $this->after    = (isset($data['after']))          ? $data['after']    : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        _ecommerce("State") => array("valor" => $this->state,"required"),
        _ecommerce("Status") => array("valor" => $this->status,"required"),
        _ecommerce("Label") => array("valor" => $this->label,"required"),
        _ecommerce("Store") => array("valor" => $this->store_id,"required"),
        _ecommerce("After") => array("valor" => $this->after,""),
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getstoreId()
    {
        return $this->store_id;
    }

    public function setStoreId($store_id)
    {
        $this->store_id = $store_id;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdateAt()
    {
        return $this->update_at;
    }

    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function setAfter($after)
    {
        $this->after = $after;
    }
 }

