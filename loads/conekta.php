<?php

function checkCustomerConekta($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) != REGISTRO_SUCCESS)
    {

        $UserModel = new \Base\model\USERS();
        if($UserModel->getData($id)==REGISTRO_SUCCESS)
        {

            $registro = $UserModel->getRows();
            try{
                $customer = \Conekta\Customer::create(
                array(
                        'name'  => $registro["nombre"],
                        'email' => $registro["email"]
                    )
                );

              //  print_r($customer);  exit;

                $CustomerEntity->token($customer->id);
                $CustomerEntity->id_categoria(1);
                $CustomerModel->save($CustomerEntity->getArrayCopy());

                return $customer;

            } catch (\Conekta_Error $e) {
                //echo $e->getMessage();
                //El pago no pudo ser procesado
            }
        }


    }

    return true;
}

function updateCustomerConekta($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();
        $UserModel = new \Base\model\USERS();
        if($UserModel->getData($id)==REGISTRO_SUCCESS)
        {
            $_registro = $UserModel->getRows();
            try{
                $customer = \Conekta\Customer::find($registro["token"]);
                $customer->update(
                  array(
                        'name'  => $_registro["nombre"],
                        'email' => $_registro["email"]
                  )
                );
                return $customer;
            } catch (\Conekta_Error $e) {
                //echo $e->getMessage();
                //El pago no pudo ser procesado
            }

        }


    }

    return true;
}

function deleteCustomerConekta($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();
        try{
            $customer = \Conekta\Customer::find($registro["token"]);
            $customer->delete();
            $CustomerEntity->id($registro["id"]);
            $CustomerModel->delete();
            return $customer;
        } catch (\Conekta_Error $e) {
            //echo $e->getMessage();
            //El pago no pudo ser procesado
        }

    }

    return true;
}


function getCustomerConekta($id)
{
    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();

        return $registro["token"];
    }
    return false;
}

function deleteCardConekta($token,$user)
{
    try{
        $customer = \Conekta\Customer::find(getCustomerConekta($user));

        foreach($customer->payment_sources as $k => $source)
        {

          if($token == $source['id'])
          {
             $source   = $customer->payment_sources[$k]->delete();
          }
        }
    } catch (\Conekta_Error $e) {
        //echo $e->getMessage();
        //El pago no pudo ser procesado
    }
}

function addCardConekta($token,$user)
{
    try{
        $customer = \Conekta\Customer::find(getCustomerConekta($user));
        $source = $customer->createPaymentSource(array(
          'token_id' => $token,
          'type'     => 'card'
        ));
        return $source;
    } catch (\Conekta_Error $e) {
        //echo $e->getMessage();
        //El pago no pudo ser procesado
    }
}
?>
