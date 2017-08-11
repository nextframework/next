<?php

/**
 * Validate Exception Class | Validate\ValidateException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate;

use Next\Components\Object;    # Object Class

/**
 * Validate Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ValidateException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x0000082B, 0x0000085D );

    /**
     * Invalid Chain Validator
     *
     * @var integer
     */
    const INVALID_CHAIN_VALIDATOR    = 0x0000082B;

    /**
     * Invalid Chain Validator
     *
     * Given Object is not a valid Validator because it doesn't
     * implements neither \Next\Validate\Validator and/or
     * \Next\Components\Interfaces\Informational interfaces
     *
     * @param \Next\Components\Object $object
     *  Object used as Validator
     *
     * @return \Next\Validate\ValidateException
     *  Exception for Invalid Validators
     */
    public static function invalidChainValidator( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Validator for the Chain

            <br /><br />

            In a Chain, Validators must implement both Validator and Informational interfaces',

            self::INVALID_CHAIN_VALIDATOR,

            (string) $object
        );
    }
}
