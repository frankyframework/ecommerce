<?php
namespace Ecommerce\Data;

class DescuentoPorcentajeData implements \Ecommerce\interfaces\EcommercePromocionInterface
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
               'name' => 'descuento_maximo',
               'label' => _ecommerce('Descuento maximo'),
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
               'name' => 'porcentaje',
               'label' => _ecommerce('Porcentaje de descuento'),
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'maxlength' => 3,
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
        $descuento = ($total * ($this->data['porcentaje']/100));
        if( $this->data['descuento_maximo'] > 0)
        {
            if($descuento > $this->data['descuento_maximo'])
            {
                $this->data['descuento_maximo'];
            }
        }
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

