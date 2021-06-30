<?php
/**
 *
 * Sr. Pago (https://srpago.com)
 *
 * @link      https://api.srpago.com
 * @copyright Copyright (c) 2016 SR PAGO
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @package   SrPago\Http
 */

namespace SrPago\Http;

use SrPago\SrPago;

/**
 * Class HttpClient
 *
 * @package SrPago\Http
 */
class HttpClient {
    static $CURL_RESPONSE_CODE = array(
        CURLINFO_EFFECTIVE_URL=>'Last effective URL',
        CURLINFO_HTTP_CODE=>'The last response code. As of PHP 5.5.0 and cURL 7.10.8, this is a legacy alias of CURLINFO_RESPONSE_CODE',
        CURLINFO_FILETIME=>'Remote time of the retrieved document, with the CURLOPT_FILETIME enabled; if -1 is returned the time of the document is unknown',
        CURLINFO_TOTAL_TIME=>'Total transaction time in seconds for last transfer',
        CURLINFO_NAMELOOKUP_TIME=>'Time in seconds until name resolving was complete',
        CURLINFO_CONNECT_TIME=>'Time in seconds it took to establish the connection',
        CURLINFO_PRETRANSFER_TIME=>'Time in seconds from start until just before file transfer begins',
        CURLINFO_STARTTRANSFER_TIME=>'Time in seconds until the first byte is about to be transferred',
        CURLINFO_REDIRECT_COUNT=>'Number of redirects, with the CURLOPT_FOLLOWLOCATION option enabled',
        CURLINFO_REDIRECT_TIME=>'Time in seconds of all redirection steps before final transaction was started, with the CURLOPT_FOLLOWLOCATION option enabled',
        CURLINFO_REDIRECT_URL=>'With the CURLOPT_FOLLOWLOCATION option disabled: redirect URL found in the last transaction, that should be requested manually next. With the CURLOPT_FOLLOWLOCATION option enabled: this is empty. The redirect URL in this case is available in CURLINFO_EFFECTIVE_URL',
        CURLINFO_PRIMARY_IP=>'IP address of the most recent connection',
        CURLINFO_PRIMARY_PORT=>'Destination port of the most recent connection',
        CURLINFO_LOCAL_IP=>'Local (source) IP address of the most recent connection',
        CURLINFO_LOCAL_PORT=>'Local (source) port of the most recent connection',
        CURLINFO_SIZE_UPLOAD=>'Total number of bytes uploaded',
        CURLINFO_SIZE_DOWNLOAD=>'Total number of bytes downloaded',
        CURLINFO_SPEED_DOWNLOAD=>'Average download speed',
        CURLINFO_SPEED_UPLOAD=>'Average upload speed',
        CURLINFO_HEADER_SIZE=>'Total size of all headers received',
        CURLINFO_HEADER_OUT=>'The request string sent. For this to work, add the CURLINFO_HEADER_OUT option to the handle by calling curl_setopt()',
        CURLINFO_REQUEST_SIZE=>'Total size of issued requests, currently only for HTTP requests',
        CURLINFO_SSL_VERIFYRESULT =>'- Result of SSL certification verification requested by setting CURLOPT_SSL_VERIFYPEER',
        CURLINFO_CONTENT_LENGTH_DOWNLOAD=>'Content length of download, read from Content-Length: field',
        CURLINFO_CONTENT_LENGTH_UPLOAD =>'- Specified size of upload',
        CURLINFO_CONTENT_TYPE =>'- Content-Type: of the requested document. NULL indicates server did not send valid Content-Type: header',
        CURLINFO_PRIVATE =>'- Private data associated with this cURL handle, previously set with the CURLOPT_PRIVATE option of curl_setopt()',
        CURLINFO_RESPONSE_CODE=>'The last response code',
        CURLINFO_HTTP_CONNECTCODE=>'The CONNECT response code',
        CURLINFO_HTTPAUTH_AVAIL=>'Bitmask indicating the authentication method(s) available according to the previous response',
        CURLINFO_PROXYAUTH_AVAIL=>'Bitmask indicating the proxy authentication method(s) available according to the previous response',
        CURLINFO_OS_ERRNO=>'Errno from a connect failure. The number is OS and system specific.',
        CURLINFO_NUM_CONNECTS=>'Number of connections curl had to create to achieve the previous transfer',
        CURLINFO_SSL_ENGINES=>'OpenSSL crypto-engines supported',
        CURLINFO_COOKIELIST =>'- All known cookies',
        CURLINFO_FTP_ENTRY_PATH=>'Entry path in FTP server',
        CURLINFO_APPCONNECT_TIME=>'Time in seconds it took from the start until the SSL/SSH connect/handshake to the remote host was completed',
        CURLINFO_CERTINFO=>'TLS certificate chain',
        CURLINFO_CONDITION_UNMET=>'Info on unmet time conditional',
        CURLINFO_RTSP_CLIENT_CSEQ=>'Next RTSP client CSeq',
        CURLINFO_RTSP_CSEQ_RECV =>'- Recently received CSeq',
        CURLINFO_RTSP_SERVER_CSEQ=>'Next RTSP server CSeq',
        CURLINFO_RTSP_SESSION_ID=>'RTSP session ID',
    );


    static $last;
    /**
     *
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     */
    public function post($url = '', $parameters = [], $headers = []) {
        return $this->request('POST',$url,$parameters);
    }

    /**
     *
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     */
    public function put($url = '', $parameters = [], $headers = []) {
        return $this->request('PUT',$url,$parameters);
    }

     /**
     *
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     */
    public function get($url = '', $parameters = [], $headers = []) {
      return $this->request('GET',$url,$parameters);
    }

     /**
     *
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     */
    public function delete($url = '', $parameters = [], $headers = []) {
      return $this->request('DELETE', $url,$parameters);
    }

    public function request($method, $endpoint, $parameters = array()){
        $endpoint = SrPago::getApiBase().SrPago::getApiVersion().$endpoint;

        $curl = curl_init();
        if ($method === 'GET' && is_array($parameters) && count($parameters) > 0) {
            $endpoint .= '?' . http_build_query($parameters);
        }

        $options = array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_USERAGENT => 'SrPago/RestClient/'.SrPago::VERSION,
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'X-User-Agent: '.SrPago::getUserAgent(),
                    'Authorization: '.SrPago::getAuthorization(),
            )
        );

        if ($method !== 'GET') {
            $options[CURLOPT_POSTFIELDS] = json_encode($parameters) ;
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        static::$last = array(
          'endpoint'=>$endpoint,
          'method'=>$method,
          'parameters'=>$parameters,
          'request'=>$options,
          'response'=>$response,
          'err'=>$err,
          'info'=>$info
        );

        $result = array("success"=>false, "error"=>array('code' => 'CommunicationException', 'message' => 'Hubo un problema al establecer la conexión con Sr. Pago','detail'=>$err));

        if(isset($info) && $info['http_code'] > 0){
            $result = json_decode($response, true);
        }
        return $result;
    }

}
