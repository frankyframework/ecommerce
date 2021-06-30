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
 * Class Base
 *
 * @package SrPago
 */
class Auth extends Base {

    /**
     * @var ENPOINT URL 
     */
    const ENDPOINT = '/auth';

    /**
     * 
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function login($username = '',$password = '') {

        $parameters = array('username' => $username, 'password'=>$password);
        $result = $this->httpClient()->post( static::ENDPOINT.'/login', $parameters);
       
        return $result;
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function logout() {
        $parameters = array();
        $result = $this->httpClient()->get( static::ENDPOINT.'/logout', $parameters);
       
        return $result;
    }
    
    /**
     * 
     * @param string $application_bundle
     * @return mixed
     */
    public function loginApplication($application_bundle = '') {
        $parameters = array('application_bundle' => $application_bundle);
        $result = $this->httpClient()->post( static::ENDPOINT . '/login/application', $parameters);
       
        return $result;
    }

}
