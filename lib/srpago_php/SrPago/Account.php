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

/**
 * Class Account
 *
 * @package SrPago
 */
class Account extends Base {

    /**
     * @var ENPOINT URL 
     */
    const ENDPOINT = '/account';

    /**
     * 
     * @return mixed
     */
    public function info() {

        $parameters = array();
        $result = $this->httpClient()->get(static::ENDPOINT.'/info', $parameters);


        return $result;
    }
    
    
    /**
     * 
     * @return mixed
     */
    public function funds() {

        $parameters = array();
        $result = $this->httpClient()->get('/funds', $parameters);


        return $result;
    }

    /**
     * 
     * @param string $username
     * @return mixed
     */
    public function recoverPassword($username) {

        $parameters = array('username' => $username);
        $result = $this->httpClient()->post(static::ENDPOINT.'/reset-password', $parameters);

        return $result;
    }

    /**
     * 
     * @param string $oldPassword
     * @param string $newPassword
     * @return mixed
     */
    public function updatePassword($oldPassword, $newPassword) {
        $parameters = array('password' => $oldPassword, 'newPassword' => $newPassword);
        $result = $this->httpClient()->post(static::ENDPOINT, $parameters);
        return $result;
    }
    
    /**
     * 
     * @param string $oldPassword
     * @param string $newPassword
     * @return mixed
     */
    public function update($parameters) {
        $result = $this->httpClient()->put(static::ENDPOINT.'/info', $parameters);
        return $result;
    }

}
