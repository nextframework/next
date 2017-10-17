<?php

/**
 * Currency Validator Class | Validate\Validators\Currency.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\Validators;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * Currency Validator Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Currency extends Object implements Validator {

    /**
     * Parameter Options Definition
     *
     * @var array $defaulOptions
     */
    protected $parameters = [

        /**
         * @internal
         *
         * Configures the Currency Validator to accept or not
         * negative numbers.
         * Defaults to TRUE, since negative numbers are valid
         * Currencies, like negative balance
         */
        'allowNegative' => TRUE,

        /**
         * Configures the Currency Validator to accept or not zeros.
         * Defaults to TRUE, since zero is a valid Currency.
         */
        'allowZero'     => TRUE
    ];

    // Validator Interface Interface Methods

    /**
     * Validates given Currency
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        // Shortening...

        $value = $this -> options -> value;

        if( ! is_string( $value ) ) {

            $this -> _error = vsprintf(

                'Validator <strong>%s</strong> expects a string, %s given',

                [
                  $this -> getClass() -> getNamespaceName(),
                  gettype( $value )
                ]
            );

            return FALSE;
        }

        if( ! $this -> options -> allowNegative && $value < 0 ) return FALSE;

        if( ! $this -> options -> allowZero && $value == 0 ) return FALSE;

        /**
         * @internal
         *
         * There are many different currency formats so, at lest for now,
         * we're going to consider as valid a string in a general
         * format of `1.234,56`
         */
        return ( preg_match( '/\b\d{1,3}(?:\.?\d{3})*(?:\,\d{2})?\b/', $value ) != 0 );
    }
}
