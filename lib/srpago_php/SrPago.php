<?php

/**
 * Created by PhpStorm.
 * User: epalacio
 * Date: 10/8/2017
 * Time: 1:51 PM
 */

include_once 'init.php';


class SrPago
{

    public function Setup(){

        \SrPago\SrPago::$apiKey = ""; //Aquí va tu App Key
        \SrPago\SrPago::$apiSecret = ""; // Aquí va tu App Secret
        \SrPago\SrPago::$liveMode = false; // false = Sandbox true = Producción

    }


    /*
    *
    *    Función para crear un nuevo Customer, necesario para tokenización OnDemand
    *
    */
    /**
     *
     * @param array $parameters
     * @return mixed
     */
    public function createCustomer($data){
        $customerService = new \SrPago\Customer();

        $newCustomer = $customerService->create($data);

        return $newCustomer;

    }

    /*
     *
     * Función para encontrar un Customer
     *
     */
    /**
     *
     * @param array $parameters
     * @return mixed
     */
    public function findCustomer($data){
        $customerService = new \SrPago\Customer();

        $newCustomer = $customerService->find($data);

        return $newCustomer;
    }


    /*
     *
     * Función para agregarle una tarjeta a un Customer (Tokenización onDemand)
     *
     */

    /**
     *
     * @param array $parameters
     * @return mixed
     */
    public function addCardToCustomer($user, $token){
        $customerCardService = new \SrPago\CustomerCards();

        $newCard = $customerCardService->add($user, $token);

        return $newCard;
    }


    /*
     *
     * Función para quitarle una tarjeta a un Customer
     *
     */

    /**
     *
     * @param string $user
     * @param string token
     * @return mixed
     */
    public function removeCustomerCard($user, $token){
        $customerCardService = new \SrPago\CustomerCards();

        $removedCard = $customerCardService->remove($user, $token);

        return $removedCard;
    }


    /*
     *
     * Función para Crear un Cargo
     *
     */

    /**
     *
     * @param array $chargeParams
     * @param array metadata
     * @return mixed
     */
    public function ChargesCreateCharge($chargeParams, $metadata){



        $chargesService = new \SrPago\Charges();

        $chargeParams['metadata'] = $metadata;


        $newCharge = $chargesService->create($chargeParams);

        return $newCharge;
    }



}

?>