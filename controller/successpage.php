<?php
use Franky\Core\ObserverManager;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer;
$id = $Tokenizer->decode($MyRequest->getRequest('order'));
$productos_comprados = getDataOrder($id);

if(empty($productos_comprados)){
         $MyRequest->redirect($MyRequest->url(CARRITO_COMPRAS));
}

$MyMetatag->setTitulo(_ecommerce("Confirmacion de pedido"));
$MyMetatag->setDescripcion(_ecommerce("Confirmacion de pedido"));
$MyMetatag->setkeywords("");
?>
