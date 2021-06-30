<?php
namespace Ecommerce\Data;

class FreeData implements \Ecommerce\interfaces\EcommerceenviosInterface
{
    public function getData(){
        
        $minimo = getCoreConfig('ecommerce/envios-free/minimo');
        $dias = getCoreConfig('ecommerce/envios-free/dias');
        
        if(empty($minimo))
        {
            return ['price' =>0,'days' => $dias];
        }
        else
        {
            $productos_comprados = getCarrito();
            if($minimo <= $productos_comprados['gran_total'] )
            {
                return ['price' =>0,'days' => $dias];
            }
            
            
        }
        return false;

    }
}

