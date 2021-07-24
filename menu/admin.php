<?php
return array(
    array('title'=> "Ecommerce",
            'children' =>  array(
        array(
         "permiso" =>   ADMINISTRAR_PEDIDOS,
         "url" => $MyRequest->url(ADMIN_LISTA_PEDIDOS),
         "etiqueta" => _ecommerce("Administrar pagos")
        ),
        array(
         "permiso" =>   ADMINISTRAR_CUPONES_ECOMMERCE,
         "url" => $MyRequest->url(ADMIN_LISTA_CUPONES_ECOMMERCE),
         "etiqueta" => _ecommerce("Administrar cupones")
        ),
        array(
            "permiso" =>   ADMINISTRAR_PROMOCIONES_ECOMMERCE,
            "url" => $MyRequest->url(ADMIN_LISTA_PROMOCIONES_ECOMMERCE),
            "etiqueta" => _ecommerce("Administrar promociones")
           ),
         array(
         "permiso" =>   ADMINISTRAR_TIENDAS_ECOMMERCE,
         "url" => $MyRequest->url(ADMIN_LISTA_TIENDAS_ECOMMERCE),
         "etiqueta" => _ecommerce("Administrar tiendas")
        ),
    )
    )

);
?>
