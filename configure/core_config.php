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
        
    'ecommerce-sales' => array(
        'menu' => "ECOMMERCE VENTAS",
        'title' => "Configuración de ventas",
        'config' =>  array(
                array('path' => 'ecommerce/ventas/address-format',
                'type' => 'text',
                'label' => 'Formato de direccion',
                'validation' => array('required' => true),
                'value' => 'Calle {calle} #numero, Colonia {colonia}, municipio {municipio},{estado} C.P. {cp}'
                ),
                array('path' => 'ecommerce/ventas/addressf-format',
                'type' => 'text',
                'label' => 'Formato de direccion',
                'validation' => array('required' => true),
                'value' => 'Nombre: {nombre}, RFC: {rfc},Calle {calle} #{numero}, Colonia {colonia}, municipio {municipio},{estado} C.P. {cp}'
                ),
                array('path' => 'ecommerce/ventas/email-template-cambiostatus',
                'type' => 'select',
                'label' => 'Template E-mail para cambio de status',
                'validation' => array('required' => true),
                'data' => getTemplatesEmail(),
                'value' => '1'
                ) ,
                array('path' => 'ecommerce/ventas/status-canceled',
                'type' => 'select',
                'label' => 'Status cancelado',
                'validation' => array('required' => true),
                'data' => getStatusTransaccion(DATA_STORE_CONFIG["id"]),
                'value' => '1'
                ) ,
                array('path' => 'ecommerce/ventas/status-invoice',
                'type' => 'select',
                'label' => 'Status invoice',
                'validation' => array('required' => true),
                'data' => getStatusTransaccion(DATA_STORE_CONFIG["id"]),
                'value' => '1'
                ) ,
                array('path' => 'ecommerce/ventas/email-sales',
                'type' => 'select',
                'label' => 'Template E-mail para nueva orden',
                'validation' => array('required' => true),
                'data' => getTemplatesEmail(),
                'value' => '1'
                )
        ),
          
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
         
            array('path' => 'ecommerce/plain_price/enabled',
                            'type' => 'select',
                            'label' => 'Habilitar metodo de envio',
                            'validation' => array('required' => true),
                            'data' => ['0' => 'No','1' => 'Sí'],
                            'value' => '0'
                          ),
            array('path' => 'ecommerce/plain_price/titulo',
                    'type' => 'text',
                    'label' => 'Titulo metodo de envio',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
            array('path' => 'ecommerce/plain_price/price',
                    'type' => 'text',
                    'label' => 'Precio o porcentaje de envio',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
                    array('path' => 'ecommerce/plain_price/iva',
                    'type' => 'text',
                    'label' => 'IVA',
                    'validation' => array('required' => true),
                    'value' => ''
                    ),
              array('path' => 'ecommerce/plain_price/tipo',
                            'type' => 'select',
                            'label' => 'Tipo de tarifa',
                            'validation' => array('required' => true),
                            'data' => ['plana' => 'Plana','porcentaje' => 'Porcentaje'],
                            'value' => 'plana'
                    ),
                    array('path' => 'ecommerce/plain_price/dias',
                    'type' => 'text',
                    'label' => 'Tiempo estimado',
                    'validation' => array('required' => true),
                    'value' => ''
                    )
            )
    ),

  'ecommerce-free-pay' => array(
          'menu' => "ECOMMERCE FREE ORDER",
          'title' => "Configuración de orden gratuita",
          'config' =>  array(
                array('path' => 'ecommerce/free_pay/name',
                        'type' => 'text',
                        'label' => 'Nombre del metodo de pago',
                        'validation' => array('required' => true),
                        'value' => 'Pedido gratuito'
                        ),
                array('path' => 'ecommerce/free_pay/enabled',
                        'type' => 'select',
                        'label' => 'Habilitar metodo de pago',
                        'validation' => array('required' => true),
                        'data' => ['0' => 'No','1' => 'Sí'],
                        'value' => '0'
                        ),
                array('path' => 'ecommerce/free_pay/email-order',
                'type' => 'select',
                'label' => 'Template E-mail orden',
                'validation' => array('required' => true),
                'data' => getTemplatesEmail(),
                'value' => ''
                )
          )
  )
);

?>