<?php
// +-----------------------------------------------------------------------+ 
                         | 
// +-----------------------------------------------------------------------+ 
// 
// $Id: CramMD5.php,v 1.4 2003/02/21 16:07:17 mj Exp $

/**
* Implmentation of CRAM-MD5 SASL mechanism
*
* @author inamujer
* @access  public
* @version 1.0
* @package Auth_SASL
*/

require_once('Auth/SASL/Common.php');

class Auth_SASL_CramMD5 extends Auth_SASL_Common
{
    /**
    * Implements the CRAM-MD5 SASL mechanism
    * This DOES NOT base64 encode the return value,
    * you will need to do that yourself.
    *
    * @param string $user      Username
    * @param string $pass      Password
    * @param string $challenge The challenge supplied by the server.
    *                          this should be already base64_decoded.
    *
    * @return string The string to pass back to the server, of the form
    *                "<user> <digest>". This is NOT base64_encoded.
    */
    function getResponse($user, $pass, $challenge)
    {
        return $user . ' ' . $this->_HMAC_MD5($pass, $challenge);
    }
}
?>