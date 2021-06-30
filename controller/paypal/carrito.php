<?php
use \Ecommerce\model\carrito;
use Ecommerce\model\carrito_producto;
$MyCarritoCompras =  new carrito();
$MyCarritoProducto =  new carrito_producto();
$productos =  OBJETO_PRODUCTOS;
$MyProducto =  new $productos();
$lista_cupones = array();
$MyCarritoProducto->setTampag(100);
$MyCarritoProducto->setOrdensql("id");



    $registro = $MyCarritoCompras->getRows();
    $id_carrito =  getMyIdCarrito();

    if($MyCarritoProducto->getData("", $id_carrito) == REGISTRO_SUCCESS)
    {
        while($registro = $MyCarritoProducto->getRows())
        {
            $MyProducto->getInfoProducto($registro["id_producto"]);
            $_registro = $MyProducto->getRows();

            $imagen = json_decode($_registro["imagen"],true);

            $carrito_compras[] = array(
                "id"                => $registro["id"],
                "id_producto"       => $registro["id_producto"],
                "caracteristicas"   => "",
                "qty"               => $registro["qty"],
                "precio"            => $_registro["precio"],
                "iva"               => $_registro["iva"],
                "nombre"            => $_registro["nombre"],
                "link"              => $MyRequest->url(DETALLE_PRODUCTOS_ECOMMERCE,$_registro),
                "imagen"            => $MyConfigure->getUploadDir()."/".DIRECTORIO_IMAGENES_PRODUCTOS_ECOMMERCE."/".$registro["id_producto"]."/".(is_array($imagen) ? $imagen[0] : $imagen)

            );
        }

    }




?>
