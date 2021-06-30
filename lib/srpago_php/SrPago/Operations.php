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
 * Class Operations
 *
 * @package SrPago
 */
class Operations extends Base {

    const ENDPOINT = '/operations';

    /**
     *
     * @param array $parameters
     * start_date (YYYY-MM-DD)
     * end_date (YYYY-MM-DD)
     * limit (int)
     * offset (int)
     * card_type (VISA,MAST,AMEX,CRNT)
     * operator (email operador)
     * charge_method (CARD,SPE)
     * @return mixed
     */
    public function all($parameters = []) {

        $result = $this->httpClient()->get(static::ENDPOINT, $parameters);

        return $result;
    }

    /**
     *
     * @param array $parameters
     * @return mixed
     */
    public function retreive($transaction) {
        $result = $this->httpClient()->get(static::ENDPOINT.'/'.$transaction);

        return $result;
    }
    
    /**
     *
     * @param array $reference
     * @return mixed
     */
    public function retreiveByReference($reference) {
        $result = $this->httpClient()->get(static::ENDPOINT.'/reference/'.$reference);

        return $result;
    }

    /**
     *
     * @param array $parameters
     * @return mixed
     */
    public function reversal($transaction) {
        $result = $this->httpClient()->get(static::ENDPOINT.'/apply-reversal/'.$transaction);

        return $result;
    }


}
