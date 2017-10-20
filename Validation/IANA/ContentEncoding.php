<?php

/**
 * IANA Content-Encoding Validator Class | Validate\IANA\ContentEncoding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\IANA;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * IANA Content Encoding Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentEncoding extends Object implements Validator {

    /**
     * Valid Content Encodings
     *
     * @see http://www.iana.org/assignments/http-parameters/http-parameters.xml#http-parameters-1
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.3
     *
     * @var string
     */
    const ENCODING = '(?:compress|deflate|exi|gzip|identity|pack200-gzip)';

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    // Validator Interface Methods

    /**
     * Validates given Content Encoding
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $test = preg_match(
            sprintf( '/^%s$/i', self::ENCODING ), $this -> options -> value
        );

        return ( $test != 0 );
    }
}