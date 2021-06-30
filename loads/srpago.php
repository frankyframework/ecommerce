<?php

function checkCustomerSrpago($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_srpago');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) != REGISTRO_SUCCESS)
    {

        $UserModel = new \Base\model\USERS();
        if($UserModel->getData($id)==REGISTRO_SUCCESS)
        {

            $registro = $UserModel->getRows();
            try{


                $customer =  array(
                    'name'  => $registro["nombre"],
                    'email' => $registro["email"]
                    
                );
                
                \SrPago\SrPago::$apiKey = getCoreConfig('ecommerce/sr-pago/key');
                \SrPago\SrPago::$apiSecret = getCoreConfig('ecommerce/sr-pago/secret');
                \SrPago\SrPago::$liveMode = (getCoreConfig('ecommerce/sr-pago/sandbox') ? false : true);
               
                $customerService = new \SrPago\Customer();

                try{
                    $customer = $customerService->create($customer);
                    if(isset($customer['result']['id']))
                    {
                        $CustomerEntity->token($customer['result']['id']);
                        $CustomerEntity->id_categoria(1);
                        $CustomerModel->save($CustomerEntity->getArrayCopy());
                    }
                }
                catch (Exception $e){
                    echo 'Error ' . $e->getMessage() . ' ' . $e->getFile();
                }
             
                
                //print_r($customer); die;
                return $customer;
                
               
                

            } catch (\OpenpayApiError $e) {
                //echo $e->getMessage();
                //El pago no pudo ser procesado
            }
        }


    }

    return true;
}

function updateCustomerSrpago($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_srpago');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {

        $registro = $CustomerModel->getRows();
        $UserModel = new \Base\model\USERS();
        if($UserModel->getData($id)==REGISTRO_SUCCESS)
        {
            $_registro = $UserModel->getRows();
            try{


                $customer =  array(
                    'name'  => $_registro["nombre"],
                    'email' => $_registro["email"]
                    
                );

                $sandbox = (getCoreConfig('ecommerce/sr-pago/sandbox') == 1 ? 'sandbox-' : '' );


                $url = "https://".$sandbox."api.srpago.com/v1/customer/".$registro["token"];

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => false,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "PUT",
                  CURLOPT_POSTFIELDS =>json_encode($customer),
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Basic ".base64_encode(getCoreConfig('ecommerce/sr-pago/key') .":". getCoreConfig('ecommerce/sr-pago/secret'))
                  ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                  //echo "cURL Error #:" . $err;
                } else {
                  //echo $response;
                }





                return $response;
            } catch (\OpenpayApiError $e) {
                //echo $e->getMessage();
                //El pago no pudo ser procesado
            }

        }


    }

    return true;
}


function deleteCustomerSrpago($id)
{

    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_srpago');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();
        try{
           

            \SrPago\SrPago::$apiKey = getCoreConfig('ecommerce/sr-pago/key');
            \SrPago\SrPago::$apiSecret = getCoreConfig('ecommerce/sr-pago/secret');
            \SrPago\SrPago::$liveMode = (getCoreConfig('ecommerce/sr-pago/sandbox') ? false : true);

            
            
            $customerService = new \SrPago\Customer();

            $customer = $customerService->remove($registro["token"]);

            return $customer;


        } catch (\OpenpayApiError $e) {
            //echo $e->getMessage();
            //El pago no pudo ser procesado
        }

    }

    return true;
}



function getCustomerSrpago($id)
{
    $CustomerModel = new \Ecommerce\model\CustomersModel();
    $CustomerEntity = new \Ecommerce\entity\CustomersEntity();
    $CustomerModel->setTable('ecommerce_customers_srpago');
    $CustomerEntity->id_user($id);

    if($CustomerModel->getData($CustomerEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $registro = $CustomerModel->getRows();

        return $registro["token"];
    }
    return false;
}

function deleteCardSrpago($token,$user)
{
    try{
       
        \SrPago\SrPago::$apiKey = getCoreConfig('ecommerce/sr-pago/key');
        \SrPago\SrPago::$apiSecret = getCoreConfig('ecommerce/sr-pago/secret');
        \SrPago\SrPago::$liveMode = (getCoreConfig('ecommerce/sr-pago/sandbox') ? false : true);


        $customerCardService = new \SrPago\CustomerCards();


        try{
            $removedCard = $customerCardService->remove(getCustomerSrpago($user),$token);
           // print_r($removedCard); die;
            
        }catch (Exception $e){
            //echo 'Error ' . $e->getMessage() . ' ' . $e->getFile();
        }

    } catch (\OpenpayApiError $e) {
        //echo $e->getMessage();
        //El pago no pudo ser procesado
    }
}

function addCardSrpago($token,$user)
{
    try{
       
        
        \SrPago\SrPago::$apiKey = getCoreConfig('ecommerce/sr-pago/key');
        \SrPago\SrPago::$apiSecret = getCoreConfig('ecommerce/sr-pago/secret');
        \SrPago\SrPago::$liveMode = (getCoreConfig('ecommerce/sr-pago/sandbox') ? false : true);

        $customerCardService = new \SrPago\CustomerCards();

        
        try{
            $newCard = $customerCardService->add(getCustomerSrpago($user), $token);
            
        }catch (Exception $e){
            //echo 'Error ' . $e->getMessage() . ' ' . $e->getFile();
        }
        //print_r($newCard); die;

        return ['id' => $newCard['result']['token']];
    } catch (\OpenpayApiError $e) {
        //echo $e->getMessage();
        //El pago no pudo ser procesado
    }
}


function pagoTarjeta($chargeParams,$metadata)
{
    \SrPago\SrPago::$apiKey = getCoreConfig('ecommerce/sr-pago/key');
    \SrPago\SrPago::$apiSecret = getCoreConfig('ecommerce/sr-pago/secret');
    \SrPago\SrPago::$liveMode = (getCoreConfig('ecommerce/sr-pago/sandbox') ? false : true);

    $chargesService = new \SrPago\Charges();

    $chargeParams['metadata'] = $metadata;

    try{
        $newCharge = $chargesService->create($chargeParams);
        return $newCharge;
    }catch (Exception $e){
        //echo 'Error ' . $e->getMessage() . ' ' . $e->getFile();
    }
    return $newCharge;
}


function pagoOXXO($chargeParams,$metadata)
{
    $chargeParams['metadata'] = $metadata;


    try{

        $sandbox = (getCoreConfig('ecommerce/sr-pago/sandbox') == 1 ? 'sandbox-' : '' );
        $url = "https://".$sandbox."api.srpago.com/v1/payment/convenience-store";

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>json_encode($chargeParams),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode(getCoreConfig('ecommerce/sr-pago/key') .":". getCoreConfig('ecommerce/sr-pago/secret'))
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          return false;
        } else {
            return json_decode($response,true);
        }





        
    } catch (\OpenpayApiError $e) {
        //
        //El pago no pudo ser procesado
        return false;
    }

}




function pagoSPEISrPago($chargeParams,$metadata)
{
    $chargeParams['metadata'] = $metadata;


    try{

        $sandbox = (getCoreConfig('ecommerce/sr-pago/sandbox') == 1 ? 'sandbox-' : '' );
        $url = "https://".$sandbox."api.srpago.com/v1/payment/spei";

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>json_encode($chargeParams),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode(getCoreConfig('ecommerce/sr-pago/key') .":". getCoreConfig('ecommerce/sr-pago/secret'))
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          return false;
        } else {
            return json_decode($response,true);
        }





        
    } catch (\OpenpayApiError $e) {
        //
        //El pago no pudo ser procesado
        return false;
    }

}

function getStatusTransaccionSrpago($status)
{
    switch ($status)
    {
        case "N":
            $_status = "paid";
        break;
    }


    return $_status;
}
?>
