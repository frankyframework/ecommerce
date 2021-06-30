<?php
use Franky\Haxor\Tokenizer;
$Tokenizer = new Tokenizer();
$status_pago = $MySession->GetVar('status_pago');
$MySession->UnsetVar('status_pago');
$pedido = $MySession->GetVar('id_pedido');
$detalle_pedido = getPedido($MySession->GetVar('id_pedido'),$MySession->GetVar('id'));
//Pendiente de programar
?>
