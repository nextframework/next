<?php

namespace Next\Validate\IANA;

use Next\Validate\Validate;    # Validate Interface

/**
 * IANA Content Encoding Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentEncoding implements Validate {

    /**
     * Valid Content Encodings
     *
     * @see http://www.iana.org/assignments/http-parameters/http-parameters.xml#http-parameters-1
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.3
     *
     * @var string
     */
    const ENCODING = '(?:compress|deflate|exi|gzip|identity|pack200-gzip)';

    // Validate Interface Methods

    /**
     * Validates given Content Encoding
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     */
    public function validate( $data ) {
        return ( preg_match( sprintf( '/^%s$/i', self::ENCODING ), $data ) != 0 );
    }
}
