<?php

/**
 * HTTP Raw Header Field Validator Class | Validation\HTTP\Headers\Raw.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers;

use Next\Components\Object;    # Object Class

/**
 * The Raw Header Validator checks if input string if valid to be used as
 * unfiltered — thus "raw" — Header Field
 *
 * Because they must be manually — and carefully — set, the only concerns
 * we take care of is for the value not to be NULL, otherwise this would
 * result in an empty string when being used and for the string to not
 * have a colon (:) which is what usually separates a Header Field name
 * from its value
 *
 * If a Raw Header must be sent with a Name, then it's not Raw, it's Generic ;)
 *
 * @package    Next\Validation
 *
 * @uses       Next\Components\Object
 *             Next\Validation\HTTP\Headers\Header
 */
class Raw extends Object implements Header {

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
                    strpos( $this -> options -> value, ':' ) === FALSE );
    }
}
