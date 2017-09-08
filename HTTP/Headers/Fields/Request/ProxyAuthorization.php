<?php

/**
 * HTTP Request Header Field Class: Proxy-Authorization | HTTP\Headers\Fields\Request\Proxy-Authorization.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields\Request;

use Next\HTTP\Headers\Fields\Request;          # Request Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * Request 'Proxy-Authorization' Header Field Validator Class
 */
use Next\Validate\HTTP\Headers\Request\ProxyAuthorization as Validator;

/**
 * Request 'Proxy-Authorization' Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ProxyAuthorization extends AbstractField implements Request {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validate\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Validator( [ 'value' => $value ] );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return [ 'name' => 'Proxy-Authorization', 'preserveWhitespace' => TRUE ];
    }
}
