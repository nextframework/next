<?php

/**
 * HTTP Entity Header Field Validator Class: Allow | Validate\Headers\Entity\Allow.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Entity;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class
use Next\Components\Utils\ArrayUtils;       # Array Utils Class

/**
 * Allow Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Allow extends Object implements Header {

    /**
     * Valid Methods
     *
     * @var string
     */
    const METHODS = [ 'OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'TRACE', 'CONNECT' ];

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Allow Header Field in according to RFC 2616 Section 14.7
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Allow   = "Allow" ":" #Method
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
     *  RFC 2616 Section 14.7
     */
    public function validate() {
        return ( ArrayUtils::search( self::METHODS, $this -> options -> value, NULL, FALSE ) !== -1 );
    }
}
