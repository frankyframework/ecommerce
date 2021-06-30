<?php
namespace Ecommerce\model;

class productos_inyectados  extends \Franky\Database\Mysql\objectOperations
{	
      
    private $objProductos;
    
    function __construct(&$objProductos, $conexion = "conexion_bd") {
        parent::__construct($conexion);
        $this->objProductos = &$objProductos;
    }
    
    function getData($id)
    {
    /*   alias: nombre, precio, precio_descuento,imagen,link*/
        return $this->objProductos->getInfoProducto($id);
    }
}


?>
