<?php
namespace Ecommerce\entity;


 
 class CarritoProductoEntity
 {
    private $id;
    private $quote_id;
    private $id_product;
    private $qty;
    private $data;
    private $price;
    private $custom_price;
    private $tax;
    private $discount;
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
        $this->id           = (isset($data['id']))              ? $data['id']               : null;
        $this->quote_id     = (isset($data['quote_id']))        ? $data['quote_id']         : null;
        $this->id_product   = (isset($data['id_product']))      ? $data['id_product']       : null;
        $this->qty          = (isset($data['qty']))             ? $data['qty']              : null;
        $this->data         = (isset($data['data']))            ? $data['data']             : null;
        $this->price        = (isset($data['price']))           ? $data['price']            : null;
        $this->custom_price = (isset($data['custom_price']))    ? $data['custom_price']     : null;
        $this->tax          = (isset($data['tax']))             ? $data['tax']              : null;
        $this->discount     = (isset($data['discount']))        ? $data['discount']         : null;
        $this->created_at   = (isset($data['created_at']))      ? $data['created_at']       : null;
        $this->update_at    = (isset($data['update_at']))       ? $data['update_at']        : null;
        
        
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
            _ecommerce("CARRITO_ID") => array("valor" => $this->quote_id,"required"),
            _ecommerce("Producto") => array("valor" => $this->id_product,"required", "numeric"),
            _ecommerce("Cantidad") => array("valor" => $this->qty,"required","numeric")
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

    public function getQuoteId()
    {
        return $this->quote_id;
    }

    public function setQuoteId($quoteId)
    {
        $this->quote_id = $quoteId;
    }

    
    public function getIdProduct()
    {
        return $this->id_product;
    }

    public function setIdProduct($idProduct)
    {
        $this->id_product = $idProduct;
    }
    
 
    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getCustomPrice()
    {
        return $this->custom_price;
    }

    public function setCustomPrice($custom_price)
    {
        $this->custom_price = $custom_price;
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
      
 }

