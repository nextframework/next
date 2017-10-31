<?php

/**
 * ISO-8601 Durations Validator Class | Validation\ISO\ISO8601\Durations.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\ISO\ISO8601;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * The ISO-8601 Durations Validator checks if input string is a valid Duration
 * in accordance to the standards of ISO-8601 Duration
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validator
 *             Next\Components\Object
 */
class Durations extends Object implements Validator {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    // Validator Interface Method Implementation

    /**
     * Validates a ISO 8601 Duration Format
     *
     * @return boolean
     *  TRUE if given value is a valid ISO 8601 Durations and FALSE otherwise
     *
     * @see http://en.wikipedia.org/wiki/Iso8601#Durations
     */
    public function validate() : bool {

        $regex = '/P
                    ([1-9][0-9]*(?:[,\.][1-9][0-9]*)?Y)?        # Years
                    ([1-9][0-9]*(?:[,\.][1-9][0-9]*)?M)?        # Months
                    ([1-9][0-9]*(?:[,\.][1-9][0-9]*)?D)?        # Days
                    (?:T
                        ([1-9][0-9]*(?:[,\.][1-9][0-9]*)?H)?    # Hours
                        ([1-9][0-9]*(?:[,\.][1-9][0-9]*)?M)?    # Minutes
                        ([1-9][0-9]*(?:[,\.][1-9][0-9]*)?S)?    # Seconds
                    )?/x';

        preg_match( $regex, strval( $this -> options -> value ), $matches );

        // Shifting the required 'P'

        array_shift( $matches );

        return ( count( $matches ) == 0 ? FALSE : TRUE );
    }
}
