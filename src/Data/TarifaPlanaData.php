<?php
namespace Ecommerce\Data;

class TarifaPlanaData implements \Ecommerce\interfaces\EcommerceenviosInterface
{
    public function getData(){
        
        $dias = getCoreConfig('ecommerce/envios-tarifa-plana/dias');
        if(getCoreConfig('ecommerce/envios-tarifa-plana/tipo') == 'plana')
        {
            return ['price' => getCoreConfig('ecommerce/envios-tarifa-plana/precio'),'days' => $dias];
        }
        if(getCoreConfig('ecommerce/envios-tarifa-plana/tipo') == 'porcentaje')
        {
            $productos_comprados = getCarrito();
           
            return ['price' => ($productos_comprados['gran_total'] * (getCoreConfig('ecommerce/envios-tarifa-plana/precio')/100)),'days' => $dias];
        }
  
    }
}

