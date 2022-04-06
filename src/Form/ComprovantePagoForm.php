<?php
namespace Ecommerce\Form;

class ComprovantePagoForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        parent::__construct();


       $this->setAtributos(array(
            'name' => $name,
            'action' => "/ecommerce/mi-cuenta/pedidos/comprovante-pago.php",
            'method' => 'post',
            'enctype' => "multipart/form-data"
        ));

        $this->add(array(
                'name' => 'callback',
                'type'  => 'hidden',

            )
        );
        $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',

            )
        );


        $this->add(array(
                'name' => 'comprovante',
                'label' => _ecommerce('Comprobante de pago'),
                'type'  => 'file',
                'atributos' => array(
                    'class' => 'required'
                 )
            )
        );


        $this->add(array(
             'name' => 'guardar',
             'type'  => 'submit',
             'atributos' => array(
                 'class' => 'btn btn-primary btn-big float_right',
                 'value' => _ecommerce("Guardar")
              )

         )
      );

    }



}
?>
