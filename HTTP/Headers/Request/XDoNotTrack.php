<?php

/**
 * HTTP Request Header Field Class: X-Do-Not-Track | HTTP\Headers\Request\XDoNotTrack.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Request;

use Next\Validation\Validator as Validators;    # Validators Interface
use Next\HTTP\Headers\Field;                    # Header Field Abstract Class

/**
 * Request Header Field Validation Class: 'X-Do-Not-Track'
 */
use Next\Validation\HTTP\Headers\Request\XDoNotTrack as Validator;

/**
 * Request Header Field: 'X-Do-Not-Track'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\XDoNotTrack
 */
class XDoNotTrack extends Field {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validation\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) : Validators {
        return new Validator( [ 'value' => $value ] );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() : array {
        return [ 'name' => 'X-Do-Not-Track' ];
    }
}
