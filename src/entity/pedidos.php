<?php
namespace Ecommerce\entity;



 class pedidos
 {
    private $id;
    private $cupon;
    private $uid;
    private $fecha;
    private $status;
    private $metodo_pago;
    private $metodo_envio;
    private $monto_envio;
    private $monto_compra;
    private $monto_pagado;
    private $id_direccion_envio;
    private $id_direccion_facturacion;
    private $referencia;
    private $fecha_pago;
    private $testigo;
    private $subtotal;
    private $iva;
    private $descuento;
    private $data_cupon;
    
    public function __construct($data = null)
    {
        if (null != $data)
        {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id']))                      ? $data['id']           : null;
        $this->cupon        = (isset($data['cupon']))                   ? $data['cupon']        : null;
        $this->uid          = (isset($data['uid']))                     ? $data['uid']          : null;
        $this->fecha        = (isset($data['fecha']))                   ? $data['fecha']        : null;
        $this->status       = (isset($data['status']))                  ? $data['status']       : null;
        $this->metodo_pago  = (isset($data['metodo_pago']))             ? $data['metodo_pago']  : null;
        $this->metodo_envio = (isset($data['metodo_envio']))            ? $data['metodo_envio'] : null;
        $this->monto_envio  = (isset($data['monto_envio']))             ? $data['monto_envio']  : null;
        $this->referencia   = (isset($data['referencia']))              ? $data['referencia']  : null;
        $this->monto_pagado  = (isset($data['monto_pagado']))             ? $data['monto_pagado']  : null;
        $this->monto_compra  = (isset($data['monto_compra']))         ? $data['monto_compra']  : null;
        $this->id_direccion_envio  = (isset($data['id_direccion_envio']))      ? $data['id_direccion_envio']  : null;
        $this->id_direccion_facturacion  = (isset($data['id_direccion_facturacion']))      ? $data['id_direccion_facturacion']  : null;
        $this->fecha_pago  = (isset($data['fecha_pago']))      ? $data['fecha_pago']  : null;
        $this->testigo  = (isset($data['testigo']))      ? $data['testigo']  : null;
        $this->subtotal  = (isset($data['subtotal']))      ? $data['subtotal']  : null;
        $this->iva  = (isset($data['iva']))      ? $data['iva']  : null;
        $this->descuento  = (isset($data['descuento']))      ? $data['descuento']  : null;
        $this->data_cupon  = (isset($data['data_cupon']))      ? $data['data_cupon']  : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getMetodo_pago()
    {
        return $this->metodo_pago;
    }
    public function getMetodo_envio()
    {
        return $this->metodo_envio;
    }

    public function getMonto_envio()
    {
        return $this->monto_envio;
    }

    public function getMonto_pagado()
    {
        return $this->monto_pagado;
    }

    public function getMonto_compra()
    {
        return $this->monto_compra;
    }


    public function getId_direccion_envio()
    {
        return $this->id_direccion_envio;
    }

    public function getId_direccion_facturacion()
    {
        return $this->id_direccion_facturacion;
    }
    public function getCupon()
    {
        return $this->cupon;
    }
    public function getReferencia()
    {
        return $this->referencia;
    }

    public function getFecha_pago()
    {
        return $this->fecha_pago;
    }

    public function getTestigo()
    {
        return $this->testigo;
    }
    public function getSubtotal()
    {
        return $this->subtotal;
    }
    public function getIva()
    {
        return $this->iva;
    }
    public function getDescuento()
    {
        return $this->descuento;
    }
    
    public function getData_cupon()
    {
        return $this->data_cupon;
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setMetodo_pago($metodo_pago)
    {
        $this->metodo_pago = $metodo_pago;
    }
    public function setMetodo_envio($metodo_envio)
    {
        $this->metodo_envio = $metodo_envio;
    }

    public function setMonto_envio($monto_envio)
    {
        $this->monto_envio = $monto_envio;
    }

    public function setMonto_pagado($monto_pagado)
    {
        $this->monto_pagado = $monto_pagado;
    }

    public function setMonto_compra($monto_compra)
    {
        $this->monto_compra = $monto_compra;
    }

    public function setId_direccion_envio($id_direccion_envio)
    {
        $this->id_direccion_envio = $id_direccion_envio;
    }

    public function setId_direccion_facturacion($id_direccion_facturacion)
    {
        $this->id_direccion_facturacion = $id_direccion_facturacion;
    }
    public function setCupon($cupon)
    {
        $this->cupon = $cupon;
    }
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }


      public function setFecha_pago($fecha_pago)
      {
          return $this->fecha_pago = $fecha_pago;
      }

      public function setTestigo($testigo)
      {
          return $this->testigo = $testigo;
      }

      public function setSubtotal($subtotal)
      {
          return $this->subtotal = $subtotal;
      }
       public function setIva($iva)
      {
          return $this->iva = $iva;
      }
    public function setDescuento($descuento)
    {
        return $this->descuento = $descuento;
    }
    public function setData_cupon($data_cupon)
    {
        return $this->data_cupon = $data_cupon;
    }


 }

?>
