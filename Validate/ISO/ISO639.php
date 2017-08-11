<?php

/**
 * ISO-639 Validator Class | Validate\ISO\ISO639.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\ISO;

use Next\Validate\Validator;    # Validator Interface

use Next\Components\Object;     # Object Class

/**
 * ISO-639 Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ISO639 extends Object implements Validator {

    /**
     * Content Language
     *
     * @staticvar array $codes
     *
     * @see http://www.loc.gov/standards/iso639-2/php/code_list.php
     */
    private static $codes = array(

        'aa', 'ab', 'af', 'ak', 'sq', 'am', 'ar', 'an', 'hy', 'as', 'av', 'ae', 'ay', 'az', 'ba', 'bm',
        'eu', 'be', 'bn', 'bh', 'bi', 'bo', 'bs', 'br', 'bg', 'my', 'ca', 'cs', 'ch', 'ce', 'zh', 'cu',
        'cv', 'kw', 'co', 'cr', 'cy', 'cs', 'da', 'de', 'dv', 'nl', 'dz', 'el', 'en', 'eo', 'et', 'eu',
        'ee', 'fo', 'fa', 'fj', 'fi', 'fr', 'fr', 'fy', 'ff', 'ka', 'de', 'gd', 'ga', 'gl', 'gv', 'el',
        'gn', 'gu', 'ht', 'ha', 'he', 'hz', 'hi', 'ho', 'hr', 'hu', 'hy', 'ig', 'is', 'io', 'ii', 'iu',
        'ie', 'ia', 'id', 'ik', 'is', 'it', 'jv', 'ja', 'kl', 'kn', 'ks', 'ka', 'kr', 'kk', 'km', 'ki',
        'rw', 'ky', 'kv', 'kg', 'ko', 'kj', 'ku', 'lo', 'la', 'lv', 'li', 'ln', 'lt', 'lb', 'lu', 'lg',
        'mk', 'mh', 'ml', 'mi', 'mr', 'ms', 'mk', 'mg', 'mt', 'mn', 'mi', 'ms', 'my', 'na', 'nv', 'nr',
        'nd', 'ng', 'ne', 'nl', 'nn', 'nb', 'no', 'ny', 'oc', 'oj', 'or', 'om', 'os', 'pa', 'fa', 'pi',
        'pl', 'pt', 'ps', 'qu', 'rm', 'ro', 'ro', 'rn', 'ru', 'sg', 'sa', 'si', 'sk', 'sk', 'sl', 'se',
        'sm', 'sn', 'sd', 'so', 'st', 'es', 'sq', 'sc', 'sr', 'ss', 'su', 'sw', 'sv', 'ty', 'ta', 'tt',
        'te', 'tg', 'tl', 'th', 'bo', 'ti', 'to', 'tn', 'ts', 'tk', 'tr', 'tw', 'ug', 'uk', 'ur', 'uz',
        've', 'vi', 'vo', 'cy', 'wa', 'wo', 'xh', 'yi', 'yo', 'za', 'zh', 'zu'
    );

    // Validator Interface Interface Methods

    /**
     * Validates given Content Language
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $data = $this -> options -> value;

        return ( strlen( $data ) == 2 && in_array( $data, self::$codes ) );
    }

    // Accessors

    /**
     * Get ISO-639 Language Abbreviations List
     *
     * @return array
     *  ISO 639 Codes
     */
    public static function getCodes() {
        return self::$codes;
    }
}
