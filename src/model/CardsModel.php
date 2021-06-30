<?php
namespace Ecommerce\model;

class CardsModel  extends \Franky\Database\Mysql\objectOperations
{


  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_cards');
  }

    function getData($card=array())
    {
        $card = $this->optimizeEntity($card);
        $campos = ["id","numero","nombre","uid","token"];

        foreach($card as $k => $v)
        {
            $this->where()->addAnd($k,$v,'=');
        }

        return $this->getColeccion($campos);

    }

    private function optimizeEntity($array)
    {
        foreach ($array as $k => $v )
        {
            if (!isset($v)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    public function save($ecommerce_cards)
    {
        $ecommerce_cards = $this->optimizeEntity($ecommerce_cards);


    	if (isset($ecommerce_cards['id']))
    	{
              $this->where()->addAnd('id',$ecommerce_cards['id'],'=');
            return $this->editarRegistro($ecommerce_cards);
    	}
    	else {

            return $this->guardarRegistro($ecommerce_cards);
    	}

    }
}
?>
