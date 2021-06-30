<?php
namespace Ecommerce\Form;

class CuponesPromocionesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        parent::__construct();

     
       $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/admin/cupones/submit.php",
            'method' => 'post'
        ));

        $this->add(array(
               'name' => 'titulo',
               'label' => 'Título',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'class' => 'required',
                   'maxlength' => 255
                ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio'
                )
           )
        );
       
        $this->add(array(
               'name' => 'codigo_promocion',
               'label' => 'Codigo cupón',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'class' => 'required',
                   'maxlength' => 32
                ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio'
                )
           )
        );

        $this->add(array(
                'name' => 'id_promocion',
                'label' => 'Tipo de promoción',
                'type'  => 'select',
                'required'  => true,

                'atributos' => array(
                    'class'       => 'required'
                 ),
                'options' =>  [],
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
    
       
        $this->add(array(
                'name' => 'fecha_inicio',
                'label' => 'Fecha de inicio',
                'type'  => 'date',
                'required'  => false,
                'atributos' => array(
                    'type_mobile' => 'date',
                    'min_year' => date('Y'),
                    'max_year' => date('Y') + 5

                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                 )
            )
        );
        
        $this->add(array(
                'name' => 'fecha_fin',
                'label' => 'Fecha de fin',
                'type'  => 'date',
                'required'  => false,
                'atributos' => array(
                    'type_mobile' => 'date',
                    'min_year' => date('Y'),
                    'max_year' => date('Y') + 5
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                 )
            )
        );
        
        $this->add(array(
               'name' => 'numero_usos',
               'label' => 'Numero usos',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                    'value' => 0,
                    'maxlength' => 5,
                   'class' => 'required'
                ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio'
                )
           )
        );
        
        $this->add(array(
               'name' => 'numero_usos_usuario',
               'label' => 'Numero usos por usuario',
               'type'  => 'text',
               'required'  => true,
               'atributos' => array(
                   'value' => 1,
                   'maxlength' => 5,
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
}
?>