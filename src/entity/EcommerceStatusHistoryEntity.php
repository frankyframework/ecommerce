<?php
namespace Ecommerce\entity;

class EcommerceStatusHistoryEntity
{
    private $id;
    private $order_id;
    private $state;
    private $status;
    private $comment;
    private $created_at;
   
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id']))                  ? $data['id']     : null;
        $this->order_id     = (isset($data['order_id']))                 ? $data['order_id']  : null;
        $this->state        = (isset($data['state']))      ? $data['state']       : null;
        $this->status       = (isset($data['status']))    ? $data['status']     : null;
        $this->comment      = (isset($data['comment']))    ? $data['comment']     : null;
        $this->created_at   = (isset($data['created_at']))          ? $data['created_at']    : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        _ecommerce("Order") => array("valor" => $this->order_id,"required"),
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

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
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

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
 }

