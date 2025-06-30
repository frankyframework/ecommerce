<?php
namespace Ecommerce\entity;


 
 class CarritoProductoEntity
 {
    private $id;
    private $quote_id;
    private $id_product;
    private $qty;
    private $url;
    private $data;
    private $price;
    private $custom_price;
    private $total_custom_price;
    private $total_discount;
    private $price_discount;
    private $total;
    private $sku;
    private $name;
    private $image;
    private $envio_requerido;
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
        $this->url          = (isset($data['url']))             ? $data['url']              : null;
        $this->data         = (isset($data['data']))            ? $data['data']             : null;
        $this->price        = (isset($data['price']))           ? $data['price']            : null;
        $this->custom_price = (isset($data['custom_price']))    ? $data['custom_price']     : null;
        $this->total_discount          = (isset($data['total_discount']))             ? $data['total_discount']              : null;
        $this->total_custom_price     = (isset($data['total_custom_price']))        ? $data['total_custom_price']         : null;
        $this->price_discount     = (isset($data['price_discount']))        ? $data['price_discount']         : null;
        $this->sku          = (isset($data['sku']))             ? $data['sku']              : null;
        $this->name         = (isset($data['name']))            ? $data['name']             : null;
        $this->image        = (isset($data['image']))           ? $data['image']            : null;
        $this->total        = (isset($data['total']))           ? $data['total']            : null;
        $this->created_at   = (isset($data['created_at']))      ? $data['created_at']       : null;
        $this->update_at    = (isset($data['update_at']))       ? $data['update_at']        : null;
        $this->envio_requerido    = (isset($data['envio_requerido']))  ? $data['envio_requerido']    : null;
        
        
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

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
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

    public function getTotalCustomPrice()
    {
        return $this->total_custom_price;
    }
    
    public function setTotalCustomPrice($totalCustomPrice)
    {
        $this->total_custom_price = $totalCustomPrice;
    }

    public function getTotalDiscount()
    {
        return $this->total_discount;
    }
    
    public function setTotalDiscount($totalDiscount)
    {
        $this->total_discount = $totalDiscount;
    }

    public function getPriceDiscount()
    {
        return $this->price_discount;
    }
    
    public function setPriceDiscount($price_discount)
    {
        $this->price_discount = $price_discount;
    }
    public function getTotal()
    {
        return $this->total;
    }
    
    public function setTotal($total)
    {
        $this->total = $total;
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

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getEnvioRequerido()
    {
        return $this->envio_requerido;
    }

    public function setEnvioRequerido($envioRequerido)
    {
        $this->envio_requerido = $envioRequerido;
    }
      
 }

