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
                'name' => 'order_id',
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
               'options' => [],
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio',

                )
           )
       );


        $this->add(array(
                'name' => 'comment',
                'label' => _ecommerce('Comentario'),
                'type'  => 'textarea',
                'required'  => false,
                'atributos' => array(

                    'value' => '',
                    'class' => ''
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
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
