<?php

$status_pago = $MySession->GetVar('status_pago');
$MySession->UnsetVar('status_pago');
$detalle_pedido = getPedido($MySession->GetVar('id_pedido'),$MySession->GetVar('id'));
//Pendiente de programar
?>
