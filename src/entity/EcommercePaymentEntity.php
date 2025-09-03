<?php
namespace Ecommerce\entity;

class EcommercePaymentEntity
{
    private $id;
    private $order_id;
    private $payment_method;
    private $amount;
    private $extra_data;
    private $is_canceled;
    private $cancel_data;
    private $created_at;
    private $update_at;
   
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id                   = (isset($data['id']))                  ? $data['id']                   : null;
        $this->order_id                  = (isset($data['order_id']))                 ? $data['order_id']                  : null;
        $this->payment_method       = (isset($data['payment_method']))      ? $data['payment_method']       : null;
        $this->amount     = (isset($data['amount']))    ? $data['amount']     : null;
        $this->extra_data         = (isset($data['extra_data']))    ? $data['extra_data']     : null;
        $this->created_at           = (isset($data['created_at']))          ? $data['created_at']           : null;
        $this->update_at           = (isset($data['update_at']))          ? $data['update_at']              : null;
        $this->is_canceled           = (isset($data['is_canceled']))          ? $data['is_canceled']              : null;
        $this->cancel_data           = (isset($data['cancel_data']))          ? $data['cancel_data']              : null;
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

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
    }
    
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getExtraData()
    {
        return $this->extra_data;
    }

    public function setExtraData($extra_data)
    {
        $this->extra_data = $extra_data;
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

    public function getIsCanceled()
    {
        return $this->is_canceled;
    }

    public function setIsCanceled($is_canceled)
    {
        $this->is_canceled = $is_canceled;
    }

    public function getCancelData()
    {
        return $this->cancel_data;
    }

    public function setCancelData($cancel_data)
    {
        $this->cancel_data = $cancel_data;
    }

 }

