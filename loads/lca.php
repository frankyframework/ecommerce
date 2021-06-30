<?php

define("CARRITO_ECOMMERCE",                     "carrito_ecommerce");
$MyAccessList->addRoll(NIVEL_USERPUBLICO,       CARRITO_ECOMMERCE);
$MyAccessList->addRoll(NIVEL_USERSUSCRIPTOR,    CARRITO_ECOMMERCE);

define("ADMINISTRAR_DIRECCIONES_ECOMMERCE",      "administrar_direcciones_ecommerce");
$MyAccessList->addRoll(NIVEL_USERSUSCRIPTOR,    ADMINISTRAR_DIRECCIONES_ECOMMERCE);

define("ADMINISTRAR_TIENDAS_ECOMMERCE",      "administrar_tiendas_ecommerce");
$MyAccessList->addRoll(NIVEL_USERSEO,     ADMINISTRAR_TIENDAS_ECOMMERCE);
$MyAccessList->addRoll(NIVEL_USERADMIN,     ADMINISTRAR_TIENDAS_ECOMMERCE);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_TIENDAS_ECOMMERCE);


define("ADMINISTRAR_MIS_PEDIDOS",      "administrar_mis_pedidos");
$MyAccessList->addRoll(NIVEL_USERSUSCRIPTOR,    ADMINISTRAR_MIS_PEDIDOS);


define("ADMINISTRAR_PEDIDOS",      "administrar_pedidos");
$MyAccessList->addRoll(NIVEL_USERADMIN,     ADMINISTRAR_PEDIDOS);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_PEDIDOS);


define("ADMINISTRAR_TARJETAS_ECOMMERCE",      "administrar_tarjetas_ecommerce");
$MyAccessList->addRoll(NIVEL_USERSUSCRIPTOR,     ADMINISTRAR_TARJETAS_ECOMMERCE);



define("ADMINISTRAR_CUPONES_ECOMMERCE",      "administrar_cupones_ecommerce");
$MyAccessList->addRoll(NIVEL_USERADMIN,     ADMINISTRAR_CUPONES_ECOMMERCE);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_CUPONES_ECOMMERCE);

define("ADMINISTRAR_PROMOCIONES_ECOMMERCE",      "administrar_promociones_ecommerce");
$MyAccessList->addRoll(NIVEL_USERADMIN,     ADMINISTRAR_PROMOCIONES_ECOMMERCE);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_PROMOCIONES_ECOMMERCE);
?>
