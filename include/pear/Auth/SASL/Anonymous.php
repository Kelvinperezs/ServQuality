<?php
// +-----------------------------------------------------------------------+ 
                           | 
// +-----------------------------------------------------------------------+ 
// 
// $Id: Anonymous.php,v 1.4 2003/02/21 16:07:17 mj Exp $

/**
* Implmentation of ANONYMOUS SASL mechanism
*
* @author inamujer
* @access  public
* @version 1.0
* @package Auth_SASL
*/

require_once('Auth/SASL/Common.php');

class Auth_SASL_Anonymous extends Auth_SASL_Common
{
    /**
    * Not much to do here except return the token supplied.
    * No encoding, hashing or encryption takes place for this
    * mechanism, simply one of:
    *  o An email address
    *  o An opaque string not containing "@" that can be interpreted
    *    by the sysadmin
    *  o Nothing
    *
    * We could have some logic here for the second option, but this
    * would by no means create something interpretable.
    *
    * @param  string $token Optional email address or string to provide
    *                       as trace information.
    * @return string        The unaltered input token
    */
    function getResponse($token = '')
    {
        return $token;
    }
}
?>