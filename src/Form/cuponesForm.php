<?php
namespace Ecommerce\Form;

class cuponesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommers/admin/cupones/submit.php",
            'method' => 'post'
        ));



        $this->add(array(
                'name' => 'codigo',
                'label' => 'Codigo cupón:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(

                    'maxlength' => 10
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
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
