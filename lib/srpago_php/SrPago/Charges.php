<?php

/**
 *
 * Sr. Pago (https://srpago.com)
 *
 * @link      https://api.srpago.com
 * @copyright Copyright (c) 2016 SR PAGO
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @package   SrPago
 */

namespace SrPago;

use SrPago\Error\SrPagoError;
use SrPago\Util\Encryption;

/**
 * Class Charges
 *
 * @package SrPago
 */
class Charges extends Base {

    const ENDPOINT = '/payment';

     /**
     *
     * @param array $_data
     * @return mixed
     */
    public function payment($_data) {
        $data = $this->mapToPayment($_data);

        $response = $this->httpClient()->post(static::ENDPOINT, $data);
        
        return $response;
    }
    
    
    /**
     *
     * @param array $_data
     * @return mixed
     */
    public function create($_data) {

        $data = $this->mapToCardPayment($_data);
        $params = Encryption::encryptParametersWithString(json_encode($data));
        if (isset($_data['metadata'])) {
            $params['metadata'] = $_data['metadata'];
        }

        $response = $this->httpClient()->post(static::ENDPOINT. '/card', $params);

        return $this->mapToResponse($response);
    }

  
    
    public function mapToResponse($response){
        if(isset($response['result']) && isset($response['result']['recipe'])){
            $response['result'] = $response['result']['recipe'];
        }
        
        return $response;
    }
 
    
    private function mapToCardPayment($parameters) {
        

        if (!isset($parameters["source"])) {

            throw new SrPagoError("source is required ad should be Dictionary");
        }


        $chargeRequest = $this->mapToSource($parameters);
        $chargeRequest['payment'] = $this->mapToPayment($parameters);
        $chargeRequest['total'] = $this->mapToPrice($parameters);
       // $chargeRequest['months'] = '1'; // Indica el nuemro de Meses, pueden ser 3,6,9,12 * sólo palican para tarjetas participantes

        foreach($parameters as $k=>$v){
            if(!isset($chargeRequest[$k])){
                $chargeRequest[$k] = $v;
            }
        }

        return $chargeRequest;
    }

    private function mapToConnect($parameters) {
        $chargeRequest = array();
        if (!isset($parameters['connect_account']) || !is_string($parameters['connect_account'])) {
            return $chargeRequest;
        }

        $chargeRequest['user_affiliated'] = $parameters['connect_account'];

        if (isset($parameters['connect_type'])) {
            $chargeRequest['type'] = $parameters['connect_type'];
        }


        if (isset($parameters['connect_fees'])) {
            if (!is_array($parameters['connect_fees'])) {
                throw new SrPagoError("connect_fees should be array");
            }
            $users = array();
            foreach ($parameters['connect_fees'] as $item) {
                if (is_array($item) && isset($item["account"]) && isset($item['amount'])) {
                    $users[] = array("user" => $item["account"], "fee" => $item['amount'],) + $item;
                }
            }
            $chargeRequest['users'] = $users;
        }

        return $chargeRequest;
    }

    private function mapToPayment($parameters) {
        if (!isset($parameters["amount"])) {
            throw new SrPagoError("amount is required ");
        }
        
        
        $paymentRQ = array();

        $paymentRQ['externa'] = array('transaction' => '');


        $paymentRQ['reference'] = array(
            'number' => isset($parameters["reference"]) ? '' . $parameters["reference"] : "",
            'description' => isset($parameters["description"]) ? '' . $parameters["description"] : "",
        );

        $paymentRQ['tip'] = array(
            'amount' => isset($parameters["tip"]) ? '' . $parameters["tip"] : "0.0",
            'currency' => isset($parameters["currency"]) ? $parameters["currency"] : "MXN",
        );

        $paymentRQ['total'] = array(
            'amount' => isset($parameters["amount"]) ? '' . $parameters["amount"] : "0.0",
            'currency' => isset($parameters["currency"]) ? $parameters["currency"] : "MXN",
        );


        $paymentRQ['origin'] = array(
            'device' => \SrPago\SrPago::getUserAgent(),
            'ip' => isset($parameters["ip"]) ? '' . $parameters["ip"] : \SrPago\SrPago::getClientIp(),
        );

        $paymentRQ['origin']['location'] = array(
            'latitude' => isset($parameters["latitude"]) ? '' . $parameters["latitude"] : "0.00000",
            'longitude' => isset($parameters["longitude"]) ? '' . $parameters["longitude"] : "0.00000",
        );
        if(isset($parameters['user'])) {
          $paymentRQ['affiliated'] = array(
            'user' => isset($parameters["user"]) ? '' . $parameters["user"] : "",
            'total_fee' => isset($parameters["total_fee"]) ? '' . $parameters["total_fee"] : "",
        );
        }
            if(isset($parameters['affiliated'])) {
                    $paymentRQ['affiliated'] = array(
                   'user' => isset($parameters["affiliated"]) ? '' . $parameters["affiliated"] : "$",  //TODO esto no existía, recomiendo que se agregue
                   'total_fee' => isset($parameters["total_fee"]) ? '' . $parameters["total_fee"] : ""
            );
        }

        $connect = $this->mapToConnect($parameters);
        if (!empty($connect)) {
            $paymentRQ['disperse'] = $connect;
        }


        return $paymentRQ;
    }

    

    private function mapToSource($parameters) {
        $chargeRequest = array();

        if (!isset($parameters['source'])) {
            throw new SrPagoException('Source is required');
        }


        if (is_string($parameters["source"])) {
            $chargeRequest['recurrent'] = '' . $parameters["source"];
        } else if (is_array($parameters["source"])) {
            $card = $this->mapToCard($parameters["source"]);
            $ecommerce = $card;

            $chargeRequest['card'] = $card;
            $ecommerce['ip'] = isset($parameters['ip']) ? $parameters['ip'] : \SrPago\SrPago::getClientIp();
            $chargeRequest['ecommerce'] = $ecommerce;
        } else {
            throw new SrPagoException();
        }

        return $chargeRequest;
    }

    private function mapToCard($source) {
        $card = array();
        $card['cvv'] = isset($source["cvv"]) ? $source["cvv"] : "";
        $card['holder_name'] = isset($source["holder_name"]) ? $source["holder_name"] : "";
        $card['expiration'] = isset($source["expiration"]) ? $source["expiration"] : "";
        $card['number'] = isset($source["number"]) ? $source["number"] : "";
        $card['raw'] = isset($source["number"]) ? $source["number"] : "";
        $card['type'] = isset($source["type"]) ? $source["type"] : "";

        return $card;
    }

    private function mapToPrice($parameters) {
        $total = array(
            'amount' => isset($parameters["amount"]) ? $parameters["amount"] : "0",
            'currency' => isset($parameters["currency"]) ? $parameters["currency"] : "MXN",
        );

        return $total;
    }

    public function all($parameters = []) {
        return (new \SrPago\Operations())->all($parameters);
    }

    public function retreive($transaction) {
        return (new \SrPago\Operations())->retreive($transaction);
    }

    public function reversal($transaction) {
        return (new \SrPago\Operations())->reversal($transaction);
    }

}
