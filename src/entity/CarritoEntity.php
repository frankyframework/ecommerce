<?php
namespace Ecommerce\entity;

class CarritoEntity
{
    private $id;
    private $uid;
    private $cookie_id;
    private $shipping_method;
    private $payment_method;
    private $shipping_address;
    private $shipping_data;
    private $invoice_address;
    private $guest;
    private $name;
    private $email;
    private $total;
    private $subtotal;
    private $tax;
    private $discount;
    private $coupon;
    private $promotion;
    private $shipping_price;
    private $shipping_subtotal;
    private $shipping_tax;
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
        $this->uid                  = (isset($data['uid']))                 ? $data['uid']                  : null;
        $this->cookie_id            = (isset($data['cookie_id']))           ? $data['cookie_id']            : null;
        $this->shipping_method      = (isset($data['shipping_method']))     ? $data['shipping_method']      : null;
        $this->payment_method       = (isset($data['payment_method']))      ? $data['payment_method']       : null;
        $this->shipping_address     = (isset($data['shipping_address']))    ? $data['shipping_address']     : null;
        $this->shipping_data         = (isset($data['shipping_data']))    ? $data['shipping_data']     : null;
        $this->invoice_address      = (isset($data['invoice_address']))     ? $data['invoice_address']      : null;
        $this->guest                = (isset($data['guest']))               ? $data['guest']                : null;
        $this->name                 = (isset($data['name']))                ? $data['name']                 : null;
        $this->email                = (isset($data['email']))               ? $data['email']                : null;
        $this->total                = (isset($data['total']))               ? $data['total']                : null;
        $this->subtotal             = (isset($data['subtotal']))            ? $data['subtotal']             : null;
        $this->tax                  = (isset($data['tax']))                 ? $data['tax']                  : null;
        $this->discount             = (isset($data['discount']))            ? $data['discount']             : null;
        $this->coupon               = (isset($data['coupon']))              ? $data['coupon']               : null;
        $this->promotion            = (isset($data['promotion']))           ? $data['promotion']            : null;
        $this->shipping_price     = (isset($data['shipping_price']))    ? $data['shipping_price']           : null;
        $this->shipping_subtotal     = (isset($data['shipping_subtotal']))    ? $data['shipping_subtotal']  : null;
        $this->shipping_tax     = (isset($data['shipping_tax']))    ? $data['shipping_tax']  : null;
        $this->created_at           = (isset($data['created_at']))          ? $data['created_at']           : null;
        $this->update_at           = (isset($data['update_at']))          ? $data['update_at']              : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        _ecommerce("COOKIE_ID") => array("valor" => $this->cookie_id,"required"),
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

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    
    public function getCookieId()
    {
        return $this->cookie_id;
    }

    public function setCookieId($cookie_id)
    {
        $this->cookie_id = $cookie_id;
    }
    
 
    public function getShipmentMethod()
    {
        return $this->shipping_method;
    }

    public function setShippingMethod($shipping_method)
    {
        $this->shipping_method = $shipping_method;
    }

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
    }
    
    public function getShippingAddres()
    {
        return $this->shipping_address;
    }

    public function setShippingAddres($shipping_address)
    {
        $this->shipping_address = $shipping_address;
    }

    public function getInvoiceAddres()
    {
        return $this->invoice_address;
    }

    public function setInvoiceAddres($invoice_address)
    {
        $this->invoice_address = $invoice_address;
    }
    
    public function getGuest()
    {
        return $this->guest;
    }
    
    public function setGuest($guest)
    {
        $this->guest = $guest;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }
    
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
    }

    public function getTax()
    {
        return $this->tax;
    }
    
    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    public function getDiscount()
    {
        return $this->discount;
    }
    
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getCoupon()
    {
        return $this->coupon;
    }
    
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
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

    public function getShippingPrice()
    {
        return $this->shipping_price;
    }

    public function setShippingPrice($shippingPrice)
    {
        $this->shipping_price = $shippingPrice;
    }

    public function getShippingSubtotal()
    {
        return $this->shipping_subtotal;
    }

    public function setShippingSubtotal($shippingSubtotal)
    {
        $this->shipping_subtotal = $shippingSubtotal;
    }

    public function getShippingTax()
    {
        return $this->shipping_tax;
    }

    public function setShippingTax($shippingTax)
    {
        $this->shipping_tax = $shippingTax;
    }

    public function getShippingData()
    {
        return $this->shipping_data;
    }

    public function setShippingData($shippingData)
    {
        $this->shipping_data = $shippingData;
    }

   
 }

