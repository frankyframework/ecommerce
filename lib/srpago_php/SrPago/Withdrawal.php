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

use SrPago\Base;

/**
 * Class Withdrawal
 *
 * @package SrPago
 */
class Withdrawal extends Base {

    const ENDPOINT = '/withdrawal';

    /**
     * 
     * @param array $parameters
     * @return mixed
     */
    public function all($parameters = []) {
        
        $result = $this->httpClient()->get(static::ENDPOINT, $parameters);
        
        return $result;
    }
}
