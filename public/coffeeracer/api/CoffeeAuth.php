<?php

require '../../../vendor/autoload.php';

class CoffeeAuth implements Luracast\Restler\iAuthenticate {

    const KEY = 'C0f33r4c3r_L4v1d4m0kk4_2015_d3ftm_4ndr01dpr4k71kUm';

    /**
     * @return string string to be used with WWW-Authenticate header
     * @example Basic
     * @example Digest
     * @example OAuth
     */
    public function __getWWWAuthenticateString()
    {
        return 'Query name="api_key"';
    }

    /**
     * Access verification method.
     *
     * API access will be denied when this method returns false
     *
     * @return boolean true when api access is allowed false otherwise
     */
    public function __isAllowed()
    {
        return isset($_REQUEST['api_key']) && $_REQUEST['api_key'] == self::KEY ? TRUE : FALSE;
    }
}