<?php
namespace Ecommerce\Form;

class checkoutForm extends \Franky\Form\Form
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


    public function addDirecionEnvio($values = array())
    {
        $this->add(array(
                'name' => 'id_envio',
                'label' => _ecommerce('Dirección de envio'),
                'type'  => 'radio',
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


    public function addDirecionFacturacion($values)
    {
        $this->add(array(
                'name' => 'id_facturacion',
                'label' => _ecommerce('Dirección de facturación'),
                'type'  => 'radio',
                'required'  => false,

                'atributos' => array(
                    'class'       => ''
                 ),
                'options' =>  $values,
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                 )
            )
        );
    }


    public function addCard($values)
    {
        $this->add(array(
                'name' => 'id_tarjeta',
                'label' => _ecommerce('Tarjeta'),
                'type'  => 'radio',
                'required'  => true,

                'atributos' => array(
                    'class'       => 'required'
                 ),
                'options' => $values,
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
    }


    public function addMetodoPago($values = array())
    {
        $this->add(array(
                'name' => 'id_pago',

                'type'  => 'radio',
                'required'  => false,
                'atributos' => array(
                    'class'       => 'required'
                 ),
                'options' =>  $values,
            )
        );
    }


    public function addMetodoEnvio($values = array())
    {
        $this->add(array(
                'name' => 'id_metodo_envio',

                'type'  => 'radio',

                'options' =>  $values,


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
                    'value' => _ecommerce("Continuar")
                 )

            )
        );
    }


}
?>
