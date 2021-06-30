<?php
namespace Ecommerce\interfaces;

interface EcommercePromocionInterface
{
    public function getForm();
    
    public function getDiscount();  
    
    public function setConfig($data);
    
    public function setUser($user);
    
    public function setCarrito($carrito);
    
}

