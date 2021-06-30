<?php
namespace Ecommerce\Form;

class direccionesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

        $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/admin/direcciones/submit.php",
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
                'label' => 'Nombre:',
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
                'name' => 'telefono',
                'label' => 'Teléfono:',

                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class' => 'required',
                    'maxlength' => 21
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );



        $this->add(array(
                'name' => 'calle',
                'label' => 'Calle:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 250
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'numero',
                'label' => 'Número:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 5
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'numeroi',
                'label' => 'Número interior:',
                'type'  => 'text',
                'required'  => false,
                'atributos' => array(

                    'maxlength' => 5
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                 )
            )
        );

        $this->add(array(
                'name' => 'cp',
                'label' => 'Código postal:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 5
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

         $this->add(array(
                'name' => 'estado',
                'label' => 'Estado:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 250
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );


          $this->add(array(
                'name' => 'ciudad',
                'label' => 'Ciudad:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 250
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

           $this->add(array(
                'name' => 'municipio',
                'label' => 'Municipio:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 250
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

            $this->add(array(
                'name' => 'colonia',
                'label' => 'Colonia:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 250
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );




    }

    public function addSubmit()
    {
          $this->add(array(
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => "Guardar"
                 )

            )
        );
    }

    public function addId()
    {
        $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',

            )
        );
    }

    public function addRFC()
    {
        $this->add(array(
                'name' => 'rfc',
                'label' => 'RFC:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 21
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
    }


    public function addOtroTelefono()
    {
        $this->add(array(
                'name' => 'telefono_otro',
                'label' => 'Otro Telefono:',
                'type'  => 'text',
                'required'  => false,
                'atributos' => array(

                    'maxlength' => 21
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                 )
            )
        );
    }


    public function addEntrecalles()
    {
         $this->add(array(
                'name' => 'entre_calle1',
                'label' => 'Entre calles y referencias:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                  'class' => 'required',
                    'maxlength' => 250
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

     
    }
    public function addInstrucciones()
    {
         $this->add(array(
                'name' => 'instrucciones',
                'label' => 'Instrucciones:',
                'type'  => 'textarea',
                'required'  => false,
                'atributos' => array(

                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                 )
            )
        );
    }
    public function addPickupPoint()
    {
        $this->add(array(
               'name' => 'pickup_point',
               'type'  => 'checkbox',
               'required'  => false,
               'atributos' => array(
                   'class'       => '',

                ),
               'options' =>  array("1" => "Este es un pick up point"),

               'label_atributos' => array(
                   'class'       => 'desc_form_no_obligatorio',
                )
           )
        );

    }
    
    public function addCheck($name,$label)
    {
        $this->add(array(
               'name' => $name,
               'type'  => 'checkbox',
               'required'  => false,
               'atributos' => array(
                   'class'       => '',

                ),
               'options' =>  array("1" => $label),

               'label_atributos' => array(
                   'class'       => 'desc_form_no_obligatorio',
                )
           )
        );

    }
    
    public function addHorario($name)
    {
        
        $horas = [];
        for($x = 0; $x <= 24; $x++){
            $horas[$x.':00'] = $x.':00';
        }
        $this->add(array(
               'name' => $name,
               //'label' => _(''),
               'type'  => 'select',
               'required'  => false,
               'atributos' => array(
                   'class'       => '',

                ),
               'options' => $horas,
               'label_atributos' => array(
                   'class'       => 'desc_form_no_obligatorio',

                )
           )
       );

    }
}
?>
