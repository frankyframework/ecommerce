<?php
namespace Ecommerce\Form;

class PromocionesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        parent::__construct();

     
       $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/admin/promociones/submit.php",
            'method' => 'post'
        ));

        $this->add(array(
               'name' => 'titulo',
               'label' => _ecommerce('Título'),
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
                'name' => 'id_promocion',
                'label' => _ecommerce('Tipo de promoción'),
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
                'label' => _ecommerce('Fecha de inicio'),
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
                'label' => _ecommerce('Fecha de fin'),
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
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => _ecommerce("Guardar")
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