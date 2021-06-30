<?php
namespace Ecommerce\Form;

class paypalBotonForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        
        parent::__construct();
     
       $this->setAtributos(array(
            'name' => $name,
            'action' => "",
            'method' => 'post'
        ));

    
        $this->add(array(
                'name' => 'cmd',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => "_xclick"
                 )
            )
        );
        
        $this->add(array(
                'name' => 'upload',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => "1"
                 )
            )
        );
      
        $this->add(array(
                'name' => 'business',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => ""
                 )
            )
        );

        
        $this->add(array(
                'name' => 'currency_code',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => ""
                 )
            )
        );
        
        $this->add(array(
                'name' => 'no_shipping',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => "1"
                 )
            )
        );
        
        $this->add(array(
                'name' => 'invoice',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => ""
                 )
            )
        );
        
        $this->add(array(
                'name' => 'pagar',
                'type'  => 'image',
                'atributos' => array(
                    
                    'value' => "¡Pagar ahora!",
                    'src' => "http://www.paypal.com/es_XC/i/btn/x-click-but01.gif"
                 )
                
            )
        );
        
    }
    
    function addAmountItem($n,$value)
    {
        $this->add(array(
                'name' => 'amount_'.$n,
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => $value
                 )
            )
        );
    }
    
    function addNameItem($n,$value)
    {
        $this->add(array(
                'name' => 'item_name_'.$n,
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => $value
                 )
            )
        );
    }
    
    function addDisconcountItem($n,$value)
    {    
         $this->add(array(
                'name' => 'discount_amount_'.$n,
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => $value
                 )
            )
        );
    }
    
    function addAmount($val)
    {    
        $this->add(array(
                'name' => 'amount',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => $val
                 )
            )
        );
    }
    
    function addItem_name($name)
    {
        $this->add(array(
                'name' => 'item_name',
                'type'  => 'hidden',
                'atributos' => array(
                    'value' => $name
                 )
            )
        );
    }

}        
   
?>