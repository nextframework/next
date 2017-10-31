<?php

/**
 * HTTP Generic Header Field Validator Class | Validation\HTTP\Headers\Generic.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers;

use Next\Components\Object;    # Object Class

/**
 * The Generic Header Validator checks if input string if valid to be used as
 * unfiltered, yet complete, Header Field
 *
 * Because they must be manually — and carefully — set, the only concerns
 * we take care of is for the value not to be NULL — otherwise this would
 * result in an empty string when being used — and for the string to have
 * a colon (:) which is what usually separates a Header Field name
 * from its value
 *
 * If a Generic Header must be sent without a Name, then it's not Generic,
 * it's Raw ;)
 *
 * @package    Next\Validation
 *
 * @uses       Next\Components\Object
 *             Next\Validation\HTTP\Headers\Header
 */
class Generic extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Generic Header Field
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() : bool {
        return ( $this -> options -> value !== NULL &&
                    strpos( $this -> options -> value, ':' ) !== FALSE );
    }
}
