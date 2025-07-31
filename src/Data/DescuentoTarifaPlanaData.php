<?php
namespace Ecommerce\Data;

class DescuentoTarifaPlanaData implements \Ecommerce\interfaces\EcommercePromocionInterface
{
    private $data;
    private $user;    
    private $total;
    
    public function getForm()
    {
        $input = array(
            array(
               'name' => 'minimo_compra',
               'label' => _ecommerce('Minimo de compra'),
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
               'label' => _ecommerce('Tarifa de descuento'),
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
        
        $total = $this->total;
        if( $this->data['minimo_compra'] > 0)
        {
            if($total < $this->data['minimo_compra'])
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
    
    public function setTotalProducts($total){
        $this->total = $total;
    }
}

