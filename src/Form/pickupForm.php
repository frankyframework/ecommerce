<?php
namespace Ecommerce\Form;

class pickupForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

        $this->setAtributos(array(
            'name' => $name,
            'action' => "",
            'method' => 'post'
        ));

    }


    public function addPickuppoints($values = array())
    {
        $this->add(array(
                'name' => 'id_pickup',
                'label' => 'Pickup Point:',
                'type'  => 'select',
                'required'  => true,

                'atributos' => array(
                    'class'       => 'required'
                 ),
                'options' =>  $values,

                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

    }

    public function addSubmit()
    {
        $this->add(array(
                'name' => 'continuar',
                'type'  => 'submit',

                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => "Continuar"
                 )

            )
        );
    }


}
?>
