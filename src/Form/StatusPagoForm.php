<?php
namespace Ecommerce\Form;

class StatusPagoForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        parent::__construct();


       $this->setAtributos(array(
            'name' => $name,
            'action' => "",
            'method' => 'post',
        ));



        $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',

            )
        );

        $this->add(array(
               'name' => 'status',
               'label' => _ecommerce('Status de pago'),
               'type'  => 'select',
               'required'  => true,
              'required'  => true,
               'atributos' => array(
                   'class'       => 'required',

                ),
               'options' => array(
                  'paid' => getStatusTransaccion('paid'),
                  'canceled' => getStatusTransaccion('canceled'),
                  'request_refunded' => getStatusTransaccion('request_refunded'),
                  'partially-refunded' => getStatusTransaccion('partially-refunded'),
                    'refunded' => getStatusTransaccion('refunded'),
               ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio',

                )
           )
       );



        $this->add(array(
                'name' => 'cantidad',
                'label' => _ecommerce('Cantidad a reenvolsar'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'maxlength' => 10,
                    'value' => '0.0',
                    'class' => 'required'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );


        $this->add(array(
                'name' => 'nota',
                'label' => _ecommerce('Nota'),
                'type'  => 'textarea',
                'required'  => true,
                'atributos' => array(

                    'value' => '',
                    'class' => 'required'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );


        $this->add(array(
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => _ecommerce("Cambiar status")
                 )

            )
        );


    }



}
?>
