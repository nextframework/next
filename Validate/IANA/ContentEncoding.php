<?php

/**
 * IANA Content-Encoding Validator Class | Validate\IANA\ContentEncoding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Validate\IANA;

use Next\Validate\Validator;    # Validator Interface

use Next\Components\Object;     # Object Class

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
