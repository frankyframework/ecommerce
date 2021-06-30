<?php
namespace Ecommerce\Data;

class DescuentoTarifaPlanaData implements \Ecommerce\interfaces\EcommercePromocionInterface
{
    private $data;
    private $user;    
    private $carrito;
    
    public function getForm()
    {
        $input = array(
            array(
               'name' => 'minimo',
               'label' => 'Minimo de compra',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'maxlength' => 10,
                   'class' => 'required'
                ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio'
                )
           ),
              array(
               'name' => 'maximo',
               'label' => 'Maximo de compra',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'maxlength' => 10,
                   'class' => 'required'
                ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio'
                )
           ),
            array(
               'name' => 'tarifa',
               'label' => 'Tarifa de descuento',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'maxlength' => 10,
                   'class' => 'required'
                ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio'
                )
           )
        );
        
        return $input;
    }
    
    public function getDiscount()
    {
        
        $total = $this->carrito['gran_total'];
        if( $this->data['minimo'] > 0)
        {
            if($total < $this->data['minimo'])
            {
                return false;
            }
        }
        if( $this->data['maximo'] > 0)
        {
            if($total > $this->data['maximo'])
            {
                return false;
            }
        }
        
        $descuento = $this->data['tarifa'];
        
        return $descuento;
    }
    
    public function setConfig($data){
        $this->data = $data;
    }
    
    public function setUser($user){
        $this->user=$user;
    }
    
    public function setCarrito($carrito){
        $this->carrito = $carrito;
    }
}

