<?php
namespace Ecommerce\entity;


 
 class direcciones
 {
    private $id;
    private $uid;
    private $status;
    private $fecha;
    private $nombre;
    private $telefono;
    private $telefono_otro;
    private $calle;
    private $numero;
    private $numeroi;
    private $cp;
    private $estado;
    private $ciudad;
    private $municipio;
    private $colonia;
    private $entre_calle1;
    private $instrucciones;

    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id               = (isset($data['id']))          ? $data['id']           : null;
        $this->uid               = (isset($data['uid']))          ? $data['uid']           : null;
        $this->status           = (isset($data['status']))      ? $data['status']       : null;
        $this->fecha            = (isset($data['fecha']))       ? $data['fecha']        : null;
        $this->nombre           = (isset($data['nombre']))      ? $data['nombre']       : null;
        $this->telefono         = (isset($data['telefono']))      ? $data['telefono']       : null;
        $this->telefono_otro    = (isset($data['telefono_otro']))      ? $data['telefono_otro']       : null;
        $this->calle            = (isset($data['calle']))      ? $data['calle']       : null;
        $this->numero           = (isset($data['numero']))      ? $data['numero']       : null;
        $this->numeroi          = (isset($data['numeroi']))      ? $data['numeroi']       : null;
        $this->cp               = (isset($data['cp']))      ? $data['cp']       : null;
        $this->estado           = (isset($data['estado']))      ? $data['estado']       : null;
        $this->ciudad           = (isset($data['ciudad']))      ? $data['ciudad']       : null;
        $this->municipio        = (isset($data['municipio']))      ? $data['municipio']       : null;
        $this->colonia          = (isset($data['colonia']))      ? $data['colonia']       : null;
        $this->entre_calle1     = (isset($data['entre_calle1']))      ? $data['entre_calle1']       : null;
        $this->instrucciones    = (isset($data['instrucciones']))      ? $data['instrucciones']       : null;

        
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        _ecommerce("Nombre") => array("valor" => $this->nombre,"required","length"=> array("max" => "255")),
        _ecommerce("Teléfono") => array("valor" => $this->telefono,"required","length"=> array("max" => "21")),
        _ecommerce("Otro Telefono") => array("valor" => $this->telefono_otro,"length"=> array("max" => "21")),
        _ecommerce("Calle") => array("valor" => $this->calle,"required"),
        _ecommerce("Numero") => array("valor" => $this->numero,"required","length"=> array("max" => "10")),
        _ecommerce("Código postal") => array("valor" => $this->cp,"required","length"=> array("max" => "5")),
        _ecommerce("Estado") => array("valor" => $this->estado,"required"),
        _ecommerce("Ciudad") => array("valor" => $this->ciudad,"required"),
        _ecommerce("Municipio") => array("valor" => $this->municipio,"required"),
        _ecommerce("Colonia") => array("valor" => $this->colonia,"required"),
        _ecommerce("Entre calle") => array("valor" => $this->entre_calle1,"required"),
        _ecommerce("Intrucciones especificas") => array("valor" => $this->instrucciones,"length"=> array("max" => "255")),
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
    
    public function getFecha()
    {
        return $this->fecha;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
   
    public function getNombre()
    {
        return $this->nombre;
    }
   
    public function getTelefono()
    {
        return $this->telefono;
    }
    
    public function getTelefono_otro()
    {
        return $this->telefono_otro;
    }
    
    public function getCalle()
    {
        return $this->calle;
    }
    
    public function getNumero()
    {
        return $this->numero;
    }
    
    public function getNumeroi()
    {
        return $this->numeroi;
    }
    
    public function getCp()
    {
        return $this->cp;
    }
    
    public function getEstado()
    {
        return $this->estado;
    }
    
    public function getCiudad()
    {
        return $this->ciudad;
    }
    
    public function getMunicipio()
    {
        return $this->municipio;
    }
    
    public function getColonia()
    {
        return $this->colonia;
    }
    
    public function getEntre_calle1()
    {
        return $this->entre_calle1;
    }
    
  
    
    public function getInstrucciones()
    {
        return $this->instrucciones;
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
    
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
   
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    
    public function setTelefono_otro($telefono_otro)
    {
        $this->telefono_otro = $telefono_otro;
    }
    
    public function setCalle($calle)
    {
        $this->calle = $calle;
    }
    
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }
    
    public function setNumeroi($numeroi)
    {
        $this->numeroi = $numeroi;
    }
    
    public function setCp($cp)
    {
        $this->cp = $cp;
    }
    
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }
    
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }
    
    public function setColonia($colonia)
    {
        $this->colonia = $colonia;
    }
    
    public function setEntre_calle1($entre_calle1)
    {
        $this->entre_calle1 = $entre_calle1;
    }
    
    
    public function setInstrucciones($instrucciones)
    {
        $this->instrucciones = $instrucciones;
    }

 }

