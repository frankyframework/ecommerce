<?php
return array(
    'ecommerce-product' => array(
        'menu' => "ECOMMERCE PRODUCTOS",
        'title' => "Configuración de productos",
        'config' =>  array(
            array('path' => 'ecommerce/product/placeholder',
                    'type' => 'file',
                    'label' => 'Placeholder del producto',
                    'validation' => array('image' => true),
                    'value' => ''
            ),
       

            array('path' => 'ecommerce/product/object',
                    'type' => 'text',
                    'label' => 'Clase del producto',
                    'validation' => array('require' => true),
                    'value' => ''
                    )    ,

            array('path' => 'ecommerce/product/path_images',
                    'type' => 'text',
                    'label' => 'Path base de imagenes del producto',
                    'validation' => array('require' => true),
                    'value' => ''
                    ),

            array('path' => 'ecommerce/product/url-detalle',
                    'type' => 'text',
                    'label' => 'Constante detalle',
                    'validation' => array('require' => true),
                    'value' => ''
                    )   
            ), 

    ),
    'ecommerce-cupones' => array(
        'menu' => "ECOMMERCE CUPONES",
        'title' => "Configuración de cupones",
        'config' =>  array(
     

        array('path' => 'ecommerce/cupones/showdelete',
                'type' => 'select',
                'label' => '¿Mostrar cupones eliminados en panel?',
                'validation' => array('require' => true),
                'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '1'
                )

          
            )
    ),
    'ecommerce-promociones' => array(
        'menu' => "ECOMMERCE PROMOCIONES",
        'title' => "Configuración de promociones",
        'config' =>  array(
     

        array('path' => 'ecommerce/promociones/showdelete',
                'type' => 'select',
                'label' => '¿Mostrar promociones eliminadas en panel?',
                'validation' => array('require' => true),
                'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '1'
                )

          
            )
    ),
    'ecommerce-envios-tarifa-plana' => array(
        'menu' => "ECOMMERCE ENVIO TARIFA PLANA",
        'title' => "Configuración de productos",
        'config' =>  array(
         
            array('path' => 'ecommerce/envios-tarifa-plana/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar metodo de envio',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
            array('path' => 'ecommerce/envios-tarifa-plana/titulo',
                    'type' => 'text',
                    'label' => 'Titulo metodo de envio',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
            array('path' => 'ecommerce/envios-tarifa-plana/precio',
                    'type' => 'text',
                    'label' => 'Precio o porcentaje de envio',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
              array('path' => 'ecommerce/envios-tarifa-plana/tipo',
                            'type' => 'select',
                            'label' => 'Tipo de tarifa',
                            'validation' => array('required' => true),
                            'data' => ['plana' => 'Plana','porcentaje' => 'Porcentaje'],
                            'value' => 'plana'
                    ),
                    array('path' => 'ecommerce/envios-tarifa-plana/dias',
                    'type' => 'text',
                    'label' => 'Tiempo estimado',
                    'validation' => array('required' => true),
                    'value' => ''
                    )
            )
    ),
    'ecommerce-envios-free' => array(
        'menu' => "ECOMMERCE ENVIO GRATUITO",
        'title' => "Configuración de envio gratuito",
        'config' =>  array(
         
            array('path' => 'ecommerce/envios-free/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar metodo de envio',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
            array('path' => 'ecommerce/envios-free/titulo',
                    'type' => 'text',
                    'label' => 'Titulo metodo de envio',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
            array('path' => 'ecommerce/envios-free/minimo',
                    'type' => 'text',
                    'label' => 'Precio minimo',
                    'validation' => array('required' => true),
                    'value' => ''
                ),
                array('path' => 'ecommerce/envios-free/dias',
                    'type' => 'text',
                    'label' => 'Tiempo estimado',
                    'validation' => array('required' => true),
                    'value' => ''
                    )
            )
    ),
    'ecommerce-pick-up' => array(
        'menu' => "ECOMMERCE RECOGER EN TIENDA",
        'title' => "Configuración de pick up",
        'config' =>  array(
         
            array('path' => 'ecommerce/pick-up/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar pick up',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
            array('path' => 'ecommerce/pick-up/titulo',
                    'type' => 'text',
                    'label' => 'Titulo metodo de envio',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
            array('path' => 'ecommerce/pick-up/precio',
                    'type' => 'text',
                    'label' => 'Precio',
                    'validation' => array('required' => true),
                    'value' => ''
                ),
                array('path' => 'ecommerce/pick-up/dias',
                    'type' => 'text',
                    'label' => 'Tiempo estimado',
                    'validation' => array('required' => true),
                    'value' => ''
                    )
            )
    ),
  'ecommerce-conekta' => array(
          'menu' => "ECOMMERCE API CONEKTA",
          'title' => "Configuración de conekta",
          'config' =>  array(
                    array('path' => 'ecommerce/conekta/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar metodo de pago',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
                      array('path' => 'ecommerce/conekta/sandbox',
                              'type' => 'select',
                              'label' => 'SANDBOX',
                              'validation' => array('required' => true),
                              'data' => ['0' => 'No','1' => 'Sí'],
                              'value' => '1'
                            ),
                      array('path' => 'ecommerce/conekta/key',
                              'type' => 'text',
                              'label' => 'API KEY',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/conekta/public',
                              'type' => 'text',
                              'label' => 'Public KEY',
                              'validation' => array('required' => false),
                              'value' => ''
                        ),
                      array('path' => 'ecommerce/conekta/keysandbox',
                              'type' => 'text',
                              'label' => 'API KEY SANDBOX',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/conekta/publicsandbox',
                              'type' => 'text',
                              'label' => 'Public KEY SANDBOX',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/conekta/methods',
                              'type' => 'select',
                              'label' => 'Metodos de pago',
                              'validation' => array('required' => false),
                              'value' => ['conekta_tarjeta','conekta_oxxo'],
                              'data' => array('conekta_tarjeta' => 'Tarjeta credito/debito',
                                              'conekta_oxxo' => 'OXXO'
                              ),
                              'multiple' => true
                            ),
               array('path' => 'ecommerce/conekta/limitcards',
                            'type' => 'text',
                            'label' => 'Limite de tarjetas',
                            'validation' => array('required' => true,'numeric' => true),
                            'value' => '3'
                          )
          )
  ),
  'ecommerce-paypal' => array(
          'menu' => "ECOMMERCE API PAYPAL",
          'title' => "Configuración de paypal",
          'config' =>  array(
                    array('path' => 'ecommerce/paypal/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar metodo de pago',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
                      array('path' => 'ecommerce/paypal/sandbox',
                              'type' => 'select',
                              'label' => 'SANDBOX',
                              'validation' => array('required' => true),
                              'data' => ['0' => 'No','1' => 'Sí'],
                              'value' => '1'
                            ),
                      array('path' => 'ecommerce/paypal/key',
                              'type' => 'text',
                              'label' => 'API KEY',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/paypal/secret',
                              'type' => 'text',
                              'label' => 'SECRET KEY',
                              'validation' => array('required' => false),
                              'value' => ''
                        ),
                      array('path' => 'ecommerce/paypal/keysandbox',
                              'type' => 'text',
                              'label' => 'API KEY SANDBOX',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/paypal/secretsandbox',
                              'type' => 'text',
                              'label' => 'SECRET KEY SANDBOX',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
             
          )
  ),
  'ecommerce-openpay' => array(
          'menu' => "ECOMMERCE API OPEPAY",
          'title' => "Configuración de openpay",
          'config' =>  array(
                    array('path' => 'ecommerce/openpay/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar metodo de pago',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
                      array('path' => 'ecommerce/openpay/sandbox',
                              'type' => 'select',
                              'label' => 'SANDBOX',
                              'validation' => array('required' => true),
                              'data' => ['0' => 'No','1' => 'Sí'],
                              'value' => '1'
                            ),
                      array('path' => 'ecommerce/openpay/id',
                              'type' => 'text',
                              'label' => 'ID',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/openpay/public',
                              'type' => 'text',
                              'label' => 'Public KEY',
                              'validation' => array('required' => false),
                              'value' => ''
                        ),
                        array('path' => 'ecommerce/openpay/secret',
                                'type' => 'text',
                                'label' => 'Secret KEY',
                                'validation' => array('required' => false),
                                'value' => ''
                          ),
                      array('path' => 'ecommerce/openpay/idsandbox',
                              'type' => 'text',
                              'label' => 'ID SANDBOX',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                      array('path' => 'ecommerce/openpay/publicsandbox',
                              'type' => 'text',
                              'label' => 'Public KEY SANDBOX',
                              'validation' => array('required' => false),
                              'value' => ''
                            ),
                            array('path' => 'ecommerce/openpay/secretsandbox',
                                    'type' => 'text',
                                    'label' => 'Secret KEY SANDBOX',
                                    'validation' => array('required' => false),
                                    'value' => ''
                                  ),
                      array('path' => 'ecommerce/openpay/methods',
                              'type' => 'select',
                              'label' => 'Metodos de pago',
                              'validation' => array('required' => false),
                              'value' => ['openpay_tarjeta','openpay_establecimiento'],
                              'data' => array('openpay_tarjeta' => 'Tarjeta credito/debito',
                                              'openpay_establecimiento' => 'Establecimientos'
                              ),
                              'multiple' => true
                            ),
                            array('path' => 'ecommerce/openpay/limitcards',
                            'type' => 'text',
                            'label' => 'Limite de tarjetas',
                            'validation' => array('required' => true,'numeric' => true),
                            'value' => '3'
                          ),
               array('path' => 'ecommerce/openpay/codewebhook',
                                    'type' => 'text',
                                    'label' => 'Codigo activación WebHook',
                                    'validation' => array('required' => false),
                                    'value' => ''
                                  ),
          )
  ),
  'ecommerce-sr-pago' => array(
        'menu' => "ECOMMERCE API SR. PAGO",
        'title' => "Configuración de Sr. pago",
        'config' =>  array(
                  array('path' => 'ecommerce/sr-pago/enabled',
                          'type' => 'select',
                          'label' => 'Habilitar metodo de pago',
                          'validation' => array('required' => true),
                          'data' => ['0' => 'No','1' => 'Sí'],
                          'value' => '0'
                        ),
                    array('path' => 'ecommerce/sr-pago/sandbox',
                            'type' => 'select',
                            'label' => 'SANDBOX',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '1'
                          ),
                    array('path' => 'ecommerce/sr-pago/key',
                            'type' => 'text',
                            'label' => 'API KEY',
                            'validation' => array('required' => false),
                            'value' => ''
                      ),
                      array('path' => 'ecommerce/sr-pago/secret',
                              'type' => 'text',
                              'label' => 'Secret KEY',
                              'validation' => array('required' => false),
                              'value' => ''
                        ),
                        array('path' => 'ecommerce/sr-pago/public',
                        'type' => 'text',
                        'label' => 'Public KEY',
                        'validation' => array('required' => false),
                        'value' => ''
                  ),
                        array('path' => 'ecommerce/sr-pago/methods',
                            'type' => 'select',
                            'label' => 'Metodos de pago',
                            'validation' => array('required' => false),
                            'value' => ['srpago_tarjeta','srpago_oxxo','srpago_spei'],
                            'data' => array('srpago_tarjeta' => 'Tarjeta credito/debito',
                                            'srpago_oxxo' => 'OXXO',
                                            'srpago_spei' => 'SPEI'
                            ),
                            'multiple' => true
                          ),
                          array('path' => 'ecommerce/sr-pago/limitcards',
                          'type' => 'text',
                          'label' => 'Limite de tarjetas',
                          'validation' => array('required' => true,'numeric' => true),
                          'value' => '3'
                        )
        )
),
);

?>
