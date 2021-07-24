<?php
namespace Ecommerce\Form;

class srpagoForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/mi-cuenta/tarjetas/submit.php",
            'method' => 'post'
        ));


        $this->add(array(
            'name' => 'tokenInput',
            'type'  => 'hidden',
        )
    );


        $this->add(array(
                'name' => 'holder_name',
                'label' => 'Nombre del tarjetahabiente:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'data-openpay-card' => 'holder_name',
                    'maxlength' => 250,
                    'autocomplete' => 'off'
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'number',
                'label' => 'Número de tarjeta de credito:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'data-openpay-card' => 'card_number',
                    'maxlength' => 16,
                    'autocomplete' => 'off'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'cvv',
                'label' => 'CVC:',
                'type'  => 'password',
                'required'  => true,
                'atributos' => array(
                    'data-openpay-card' => 'cvv2',
                    'maxlength' => 3,
                    'placeholder' => '3 o 4 dígitos del reverso',
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'month',
                'label' => 'Mes de expiración (MM):',
                'type'  => 'select',
                'required'  => true,
                'atributos' => array(
                    'data-openpay-card' => 'expiration_month',
                 ),
                'options' => array(
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                    '05' => '05',
                    '06' => '06',
                    '07' => '07',
                    '08' => '08',
                    '09' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',

                  ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'year',
                'label' => 'Año de expiración (AAAA):',
                'type'  => 'select',
                'required'  => true,
                'atributos' => array(
                    'data-openpay-card' => 'expiration_year',
                 ),
             'options' => array(
                    date('y') => date('Y'),
                    date('y')+1 => date('Y')+1,
                    date('y')+2 => date('Y')+2,
                    date('y')+3 => date('Y')+3,
                    date('y')+4 => date('Y')+4,
                    date('y')+5 => date('Y')+5,
                    date('y')+6 => date('Y')+6,
                    date('y')+7 => date('Y')+7

                  ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );


        $this->add(array(
                'name' => 'pagar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => "¡Pagar ahora!"
                 )

            )
        );

    }


    function addCheckGuardar()
    {
         $this->add(array(
                'name' => 'save',
                'label' => 'Guardar tarjeta:',
                'type'  => 'checkbox',
                'required'  => false,
                'options' =>array("1" => "Guardar tarjeta")

            )
        );
    }

}
?>
