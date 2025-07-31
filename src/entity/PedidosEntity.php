<?php
namespace Ecommerce\entity;

class PedidosEntity
{
    private $id;
    private $uid;
    private $quote_id;
    private $order_id;
    private $status;
    private $state;
    private $payment_total;
    private $is_invoiced;
    private $is_shipping;
    private $is_canceled;
    private $shipping_method;
    private $payment_method;
    private $shipping_address;
    private $shipping_data;
    private $invoice_address;
    private $guest;
    private $name;
    private $email;
    private $total;
    private $total_items;
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
        $this->quote_id            = (isset($data['quote_id']))           ? $data['quote_id']            : null;
        $this->order_id            = (isset($data['order_id']))           ? $data['order_id']            : null;
        $this->shipping_method      = (isset($data['shipping_method']))     ? $data['shipping_method']      : null;
        $this->payment_method       = (isset($data['payment_method']))      ? $data['payment_method']       : null;
        $this->shipping_address     = (isset($data['shipping_address']))    ? $data['shipping_address']     : null;
        $this->shipping_data         = (isset($data['shipping_data']))    ? $data['shipping_data']     : null;
        $this->invoice_address      = (isset($data['invoice_address']))     ? $data['invoice_address']      : null;
        $this->guest                = (isset($data['guest']))               ? $data['guest']                : null;
        $this->name                 = (isset($data['name']))                ? $data['name']                 : null;
        $this->email                = (isset($data['email']))               ? $data['email']                : null;
        $this->total                = (isset($data['total']))               ? $data['total']                : null;
        $this->total_items          = (isset($data['total_items']))               ? $data['total_items']                : null;
        $this->subtotal             = (isset($data['subtotal']))            ? $data['subtotal']             : null;
        $this->tax                  = (isset($data['tax']))                 ? $data['tax']                  : null;
        $this->discount             = (isset($data['discount']))            ? $data['discount']             : null;
        $this->coupon               = (isset($data['coupon']))              ? $data['coupon']               : null;
        $this->promotion            = (isset($data['promotion']))           ? $data['promotion']            : null;
        $this->shipping_price     = (isset($data['shipping_price']))    ? $data['shipping_price']           : null;
        $this->shipping_subtotal     = (isset($data['shipping_subtotal']))    ? $data['shipping_subtotal']  : null;
        $this->shipping_tax     = (isset($data['shipping_tax']))    ? $data['shipping_tax']  : null;
        $this->status     = (isset($data['status']))    ? $data['status']  : null;
        $this->state     = (isset($data['state']))    ? $data['state']  : null;
        $this->payment_total     = (isset($data['payment_total']))    ? $data['payment_total']  : null;
        $this->is_invoiced     = (isset($data['is_invoiced']))    ? $data['is_invoiced']  : null;
        $this->is_shipping     = (isset($data['is_shipping']))    ? $data['is_shipping']  : null;
        $this->is_canceled     = (isset($data['is_canceled']))    ? $data['is_canceled']  : null;
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
        _ecommerce("quote_id") => array("valor" => $this->quote_id,"required"),
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

    public function getTotalItems()
    {
        return $this->total_items;
    }

    public function setTotalItems($totalItems)
    {
        $this->total_items = $totalItems;
    }

    public function getQuoteId()
    {
        return $this->quote_id;
    }

    public function setQuoteId($quote_id)
    {
        $this->quote_id = $quote_id;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getPaymentTotal()
    {
        return $this->payment_total;
    }

    public function setPaymentTotal($payment_total)
    {
        $this->payment_total = $payment_total;
    }

    public function getIsInvoiced()
    {
        return $this->is_invoiced;
    }

    public function setIsInvoiced($is_invoiced)
    {
        $this->is_invoiced = $is_invoiced;
    }

    public function getIsShipping()
    {
        return $this->is_shipping;
    }

    public function setIsShipping($is_shipping)
    {
        $this->is_shipping = $is_shipping;
    }

    public function getIsCanceled()
    {
        return $this->is_canceled;
    }

    public function setIsCanceled($is_canceled)
    {
        $this->is_canceled = $is_canceled;
    }
 }

