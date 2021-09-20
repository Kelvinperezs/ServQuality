<?php
// +-----------------------------------------------------------------------+ 
                            | 
// +-----------------------------------------------------------------------+ 
// 
// $Id: Common.php,v 1.6 2003/02/21 16:07:17 mj Exp $

/**
* Common functionality to SASL mechanisms
*
* @author  inamujer
* @access  public
* @version 1.0
* @package Auth_SASL
*/

class Auth_SASL_Common
{
    /**
    * Function which implements HMAC MD5 digest
    *
    * @param  string $key  The secret key
    * @param  string $data The data to protect
    * @return string       The HMAC MD5 digest
    */
    function _HMAC_MD5($key, $data)
    {
        if (strlen($key) > 64) {
            $key = pack('H32', md5($key));
        }

        if (strlen($key) < 64) {
            $key = str_pad($key, 64, chr(0));
        }

        $k_ipad = substr($key, 0, 64) ^ str_repeat(chr(0x36), 64);
        $k_opad = substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64);

        $inner  = pack('H32', md5($k_ipad . $data));
        $digest = md5($k_opad . $inner);

        return $digest;
    }
}
?>
