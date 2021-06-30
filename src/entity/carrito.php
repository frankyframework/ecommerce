<?php
namespace Ecommerce\entity;


 
 class carrito
 {
    private $id;
    private $uid;
    private $cookie_id;
    private $invoice;
    private $id_envio;
    private $id_facturacion;
    private $id_cupon;
    private $id_pago;
   
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id               = (isset($data['id']))              ? $data['id']               : null;
        $this->uid              = (isset($data['uid']))             ? $data['uid']              : null;
        $this->cookie_id        = (isset($data['cookie_id']))       ? $data['cookie_id']        : null;
        $this->invoice          = (isset($data['invoice']))         ? $data['invoice']          : null;
        $this->id_envio         = (isset($data['id_envio']))        ? $data['id_envio']         : null;
        $this->id_facturacion   = (isset($data['id_facturacion']))  ? $data['id_facturacion']   : null;
        $this->id_cupon         = (isset($data['id_cupon']))        ? $data['id_cupon']         : null;
        $this->id_pago          = (isset($data['id_pago']))         ? $data['id_pago']          : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        _ecommerce("UID") => array("valor" => $this->uid,"required","numeric"),
        _ecommerce("COOKIE_ID") => array("valor" => $this->id_cookie,"required"),
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUid()
    {
        return $this->uid;
    }
    
    public function getCookie_id()
    {
        return $this->cookie_id;
    }
    
 
    public function getInvoice()
    {
        return $this->invoice;
    }
    
    public function getId_envio()
    {
        return $this->id_envio;
    }
    
    public function getId_facturacion()
    {
        return $this->id_facturacion;
    }
    
    public function getId_cupon()
    {
        return $this->id_cupon;
    }
    
    public function getId_pago()
    {
        return $this->id_pago;
    }
   
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function setCookie_id($cookie_id)
    {
        $this->cookie_id = $cookie_id;
    }
    
  
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }
    
     public function setId_envio($id_envio)
    {
        $this->id_envio = $id_envio;
    }
    
    public function setId_facturacion($id_facturacion)
    {
        $this->id_facturacion = $id_facturacion;
    }
    
    public function setId_cupon($id_cupon)
    {
        $this->id_cupon = $id_cupon;
    }
    
    public function setId_pago($id_pago)
    {
        $this->id_pago = $id_pago;
    }
   
 }

