<?php

/**
 * ISO-639 Validator Class | Validation\ISO\ISO639.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\ISO;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * The ISO-639 Validator checks if input string is a valid
 * Language Name Representation in accordance to the standards of ISO-639
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validator
 *             Next\Components\Object
 */
class ISO639 extends Object implements Validator {

    /**
     * Content Language
     *
     * @staticvar array $codes
     *
     * @see http://www.loc.gov/standards/iso639-2/php/code_list.php
     */
    const CODES = [
        'aa', 'ab', 'af', 'ak', 'sq', 'am', 'ar', 'an', 'hy', 'as', 'av', 'ae',
        'ay', 'az', 'ba', 'bm', 'eu', 'be', 'bn', 'bh', 'bi', 'bo', 'bs', 'br',
        'bg', 'my', 'ca', 'cs', 'ch', 'ce', 'zh', 'cu', 'cv', 'kw', 'co', 'cr',
        'cy', 'cs', 'da', 'de', 'dv', 'nl', 'dz', 'el', 'en', 'eo', 'et', 'eu',
        'ee', 'fo', 'fa', 'fj', 'fi', 'fr', 'fr', 'fy', 'ff', 'ka', 'de', 'gd',
        'ga', 'gl', 'gv', 'el', 'gn', 'gu', 'ht', 'ha', 'he', 'hz', 'hi', 'ho',
        'hr', 'hu', 'hy', 'ig', 'is', 'io', 'ii', 'iu', 'ie', 'ia', 'id', 'ik',
        'is', 'it', 'jv', 'ja', 'kl', 'kn', 'ks', 'ka', 'kr', 'kk', 'km', 'ki',
        'rw', 'ky', 'kv', 'kg', 'ko', 'kj', 'ku', 'lo', 'la', 'lv', 'li', 'ln',
        'lt', 'lb', 'lu', 'lg', 'mk', 'mh', 'ml', 'mi', 'mr', 'ms', 'mk', 'mg',
        'mt', 'mn', 'mi', 'ms', 'my', 'na', 'nv', 'nr', 'nd', 'ng', 'ne', 'nl',
        'nn', 'nb', 'no', 'ny', 'oc', 'oj', 'or', 'om', 'os', 'pa', 'fa', 'pi',
        'pl', 'pt', 'ps', 'qu', 'rm', 'ro', 'ro', 'rn', 'ru', 'sg', 'sa', 'si',
        'sk', 'sk', 'sl', 'se', 'sm', 'sn', 'sd', 'so', 'st', 'es', 'sq', 'sc',
        'sr', 'ss', 'su', 'sw', 'sv', 'ty', 'ta', 'tt', 'te', 'tg', 'tl', 'th',
        'bo', 'ti', 'to', 'tn', 'ts', 'tk', 'tr', 'tw', 'ug', 'uk', 'ur', 'uz',
        've', 'vi', 'vo', 'cy', 'wa', 'wo', 'xh', 'yi', 'yo', 'za', 'zh', 'zu'
    ];

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    // Validator Interface Interface Methods

    /**
     * Validates given Content Language
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() : bool {

        $value = $this -> options -> value;

        if( ! is_string( $value ) ) return FALSE;

        /**
         * @internal
         *
         * Removing non-English characters and modifying input data
         * letter case
         */
        $value = mb_strtolower(
            preg_replace('/[^\00-\255]+/u', '', $value )
        );

        /**
         * @internal
         *
         * Providing cleaned and treated input value to be
         * accessed outside the Validator
         *
         * This way the routine above doesn't need to be done again if,
         * whoever called this Validator needs the input value treated
         */
        $this -> _info = $value;

        return ( strlen( $value ) == 2 && in_array( $value, self::CODES ) );
    }
}
