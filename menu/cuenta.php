<?php
return array(
    array('title'=> "Ecommerce",
            'children' =>  array(
   
        array(
         "permiso" =>   "administrar_direcciones_ecommerce",
         "url" => $MyRequest->url(ADMIN_LISTA_DIRECCIONES_ECOMMERCE),
         "etiqueta" => _ecommerce("Mis direcciones de entrega")
        ),
        array(
         "permiso" =>   "administrar_direcciones_ecommerce",
         "url" => $MyRequest->url(ADMIN_LISTA_DIRECCIONES_FACTURACION_ECOMMERCE),
         "etiqueta" => _ecommerce("Mis direcciones de facturacion")
        ),

        array(
         "permiso" =>   "administrar_tarjetas_ecommerce",
         "url" => $MyRequest->url(ADMIN_LISTA_TARJETAS_ECOMMERCE),
         "etiqueta" => _ecommerce("Mis tarjetas")
        ),
        array(
         "permiso" =>   "administrar_mis_pedidos",
         "url" => $MyRequest->url(MICUENTA_LISTA_PEDIDOS),
         "etiqueta" => _ecommerce("Mis pedidos")
        )
    )
    )

);
?>
