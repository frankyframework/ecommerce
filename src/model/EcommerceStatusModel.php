<?php
namespace Ecommerce\model;

class EcommerceStatusModel  extends \Franky\Database\Mysql\objectOperations
{

  private string $busca;
  public function __construct()
  {
    parent::__construct();
    $this->from()->addTable('ecommerce_status');
  }

  public function setBusca($busca){
    $this->busca = $busca;
  }

  function getData($data=array())
  {
      $data = $this->optimizeEntity($data);
      $campos = array("id","store_id","state","status","label","created_at","update_at","active","after");

      foreach($data as $k => $v)
      {
          $this->where()->addAnd("ecommerce_status.".$k,$v,'=');
      } 

      if(!empty($this->busca))
      {
            $this->where()->concat('AND (');
            $this->where()->addOr('status','%'.$this->busca.'%','like');
            $this->where()->addOr('state','%'.$this->busca.'%','like');
            $this->where()->concat(')');
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

    function getExist($id,$status,$state,$store)
    {
      $campos = array("id");

      if(!empty($id))
      {
        $this->where()->addAnd("ecommerce_status.id",$id,'!=');
      }
      $this->where()->addAnd("ecommerce_status.state",$state,'=');
      $this->where()->addAnd("ecommerce_status.status",$status,'=');
      $this->where()->addAnd("ecommerce_status.store_id",$store,'=');

      return $this->getColeccion($campos);

    }

    public function delete($id)
    {
        $this->where()->addAnd('id',$id,'=');
        return $this->eliminarRegistro();
    }


    public function save($data)
    {
      $data = $this->optimizeEntity($data);


    	if (isset($data['id']))
    	{
          $this->where()->addAnd('id',$data['id'],'=');
            return $this->editarRegistro($data);
    	}
    	else {

            return $this->guardarRegistro($data);
    	}

    }
}


?>
