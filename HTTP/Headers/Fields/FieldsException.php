<?php

/**
 * HTTP Header Fields Exception Class | HTTP\Headers\Fields\FieldsException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields;

/**
 * Headers Fields Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class FieldsException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x0000042F, 0x00000461 );

    /**
     * All Header Field Values are invalid
     *
     * @var integer
     */
    const ALL_INVALID    = 0x0000042F;

    // Exception Messages

    /**
     * All values assigned to Header Field are invalid
     *
     * @param string $header
     *  Header Name
     *
     * @return \Next\HTTP\Headers\Fields\FieldsException
     *  Exception for invalid header value(s)
     */
    public static function invalidHeaderValue( $header ) {

        return new self(

            'All values assigned to <strong>%s</strong> Header are invalid',

            self::ALL_INVALID,

            $header
        );
    }
}
