<?php
namespace Ecommerce\Form;

class StatusForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        parent::__construct();


       $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/admin/status/submit.php",
            'method' => 'post',
        ));



        $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',

            )
        );

        $this->add(array(
                'name' => 'store_id',
                'label' => _ecommerce('Store'),
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
               'name' => 'state',
               'label' => _ecommerce('Status base de pago'),
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
                'name' => 'status',
                'label' => _ecommerce('Status'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'maxlength' => 100,
                    'class' => 'required'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
            'name' => 'after',
            'label' => _ecommerce('Despues de'),
            'type'  => 'select',
            'required'  => true,
           'required'  => true,
            'atributos' => array(
                'class'       => '',

             ),
            'options' => [],
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio',

             )
            )
        );

        $this->add(array(
            'name' => 'label',
            'label' => _ecommerce('Label'),
            'type'  => 'text',
            'required'  => true,
            'atributos' => array(
                'maxlength' => 100,
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
                    'value' => _ecommerce("Guardar status")
                 )

            )
        );


    }



}
?>
