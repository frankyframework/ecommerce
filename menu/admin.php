<?php
return array(
    array('title'=> "Ecommerce",
            'children' =>  array(
        array(
         "permiso" =>   "administrar_pedidos",
         "url" => $MyRequest->url(ADMIN_LISTA_PEDIDOS),
         "etiqueta" => _ecommerce("Administrar pagos")
        ),
        array(
         "permiso" =>   "administrar_cupones_ecommerce",
         "url" => $MyRequest->url(ADMIN_LISTA_CUPONES_ECOMMERCE),
         "etiqueta" => _ecommerce("Administrar cupones")
        ),
        array(
            "permiso" =>   "administrar_promociones_ecommerce",
            "url" => $MyRequest->url(ADMIN_LISTA_PROMOCIONES_ECOMMERCE),
            "etiqueta" => _ecommerce("Administrar promociones")
           ),
         array(
         "permiso" =>   "administrar_tiendas_ecommerce",
         "url" => $MyRequest->url(ADMIN_LISTA_TIENDAS_ECOMMERCE),
         "etiqueta" => _ecommerce("Administrar tiendas")
        ),
    )
    )

);
?>
