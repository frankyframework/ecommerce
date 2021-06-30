<?php
namespace Ecommerce\Data;

class PickupData implements \Ecommerce\interfaces\EcommerceenviosInterface
{
    public function getData(){
        
        $precio = getCoreConfig('ecommerce/pick-up/precio');
        $dias = getCoreConfig('ecommerce/pick-up/dias');
      
        return ['price' =>$precio,'days' => $dias];
     

    }
}

