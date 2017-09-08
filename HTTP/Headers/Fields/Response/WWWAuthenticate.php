<?php

/**
 * HTTP Response Header Field Class: WWW-Authenticate | HTTP\Headers\Fields\Response\WWWAuthenticate.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields\Response;

use Next\HTTP\Headers\Fields\Response;         # Response Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * Response 'WWW-Authenticate' Header Field Validator Class
 */
use Next\Validate\HTTP\Headers\Response\WWWAuthenticate as Validator;

/**
 * Response 'WWW-Authenticate' Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class WWWAuthenticate extends AbstractField implements Response {

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
        return [ 'name' => 'WWW-Authenticate', 'preserveWhitespace' => TRUE ];
    }
}
