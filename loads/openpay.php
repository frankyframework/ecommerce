<?php

function checkCustomerOpenpay($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_openpay');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) != REGISTRO_SUCCESS)
    {

        $UserModel = new \Base\model\USERS();
        if($UserModel->getData($id)==REGISTRO_SUCCESS)
        {

            $registro = $UserModel->getRows();
            try{

                $openpay = Openpay::getInstance((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/idsandbox') :  getCoreConfig('ecommerce/openpay/id')),
                 (getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/secretsandbox') :  getCoreConfig('ecommerce/openpay/secret')));

                Openpay::setProductionMode((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? false : true));

                $customer =  $openpay->customers->add(
                array(
                        'name'  => $registro["nombre"],
                        'last_name'  => '',
                        'email' => $registro["email"]
                    )
                );

               //print_r($customer);

                $CustomerEntity->token($customer->id);
                $CustomerEntity->id_categoria(1);
                $CustomerModel->save($CustomerEntity->getArrayCopy());

                return $customer;

            } catch (\OpenpayApiError $e) {
                //echo $e->getMessage();
                //El pago no pudo ser procesado
            }
        }


    }

    return true;
}

function updateCustomerOpenpay($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_openpay');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();
        $UserModel = new \Base\model\USERS();
        if($UserModel->getData($id)==REGISTRO_SUCCESS)
        {
            $_registro = $UserModel->getRows();
            try{

              $openpay = Openpay::getInstance((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/idsandbox') :  getCoreConfig('ecommerce/openpay/id')),
               (getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/secretsandbox') :  getCoreConfig('ecommerce/openpay/secret')));

              Openpay::setProductionMode((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? false : true));

                $customer = $openpay->customers->get($registro["token"]);
                $customer->name  = $_registro["nombre"];
                $customer->email = $_registro["email"];
                $customer->save();

                return $customer;
            } catch (\OpenpayApiError $e) {
                //echo $e->getMessage();
                //El pago no pudo ser procesado
            }

        }


    }

    return true;
}

function deleteCustomerOpenpay($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_openpay');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();
        try{
          $openpay = Openpay::getInstance((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/idsandbox') :  getCoreConfig('ecommerce/openpay/id')),
           (getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/secretsandbox') :  getCoreConfig('ecommerce/openpay/secret')));

          Openpay::setProductionMode((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? false : true));


            $customer = $openpay->customers->get($registro["token"]);
            $customer->delete();
            $CustomerEntity->id($registro["id"]);
            $CustomerModel->delete($CustomerEntity->getArrayCopy());
            return $customer;
        } catch (\OpenpayApiError $e) {
            //echo $e->getMessage();
            //El pago no pudo ser procesado
        }

    }

    return true;
}


function getCustomerOpenpay($id)
{
    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_openpay');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();

        return $registro["token"];
    }
    return false;
}

function deleteCardOpenpay($token,$user)
{
    try{
      $openpay = Openpay::getInstance((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/idsandbox') :  getCoreConfig('ecommerce/openpay/id')),
       (getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/secretsandbox') :  getCoreConfig('ecommerce/openpay/secret')));

      Openpay::setProductionMode((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? false : true));

        $customer = $openpay->customers->get(getCustomerOpenpay($user));
        $card = $customer->cards->get($token);
        $card->delete();

    } catch (\OpenpayApiError $e) {
        //echo $e->getMessage();
        //El pago no pudo ser procesado
    }
}

function addCardOpenpay($token,$user,$device_session)
{
    try{
      $openpay = Openpay::getInstance((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/idsandbox') :  getCoreConfig('ecommerce/openpay/id')),
       (getCoreConfig('ecommerce/openpay/sandbox') == 1 ? getCoreConfig('ecommerce/openpay/secretsandbox') :  getCoreConfig('ecommerce/openpay/secret')));

      Openpay::setProductionMode((getCoreConfig('ecommerce/openpay/sandbox') == 1 ? false : true));


        $customer = $openpay->customers->get(getCustomerOpenpay($user));
        $source = $customer->cards->add(array(
          'token_id' => $token,
          'device_session_id'     => $device_session
        ));

        return ['id' => $source->id];
    } catch (\OpenpayApiError $e) {
        //echo $e->getMessage();
        //El pago no pudo ser procesado
    }
}
?>
