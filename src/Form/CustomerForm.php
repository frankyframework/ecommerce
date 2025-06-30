<?php
namespace Ecommerce\Form;

class CustomerForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

        $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/checkout/customer/submit.php",
            'method' => 'post'
        ));

        $this->add(array(
                'name' => 'callback',
                'type'  => 'hidden',
                'required'  => false,

            )
        );


        $this->add(array(
                'name' => 'nombre',
                'label' => _ecommerce('Nombre'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class' => 'required',
                    'maxlength' => 200
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'email',
                'label' => _ecommerce('E-mail'),

                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class' => 'required email',
                    'maxlength' => 255,
                    'type_mobile'  => 'email'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
            'name' => 'siguiente',
            'type'  => 'submit',
            'atributos' => array(
                'class'       => 'btn btn-primary btn-big float_right',
                'value' => _ecommerce("Siguiente")
             )

        )
    );

    }
}
?>
