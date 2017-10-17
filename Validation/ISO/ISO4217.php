<?php

/**
 * ISO-4217 Validator Class | Validate\ISO\ISO4217.php
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
 * ISO-4217 Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ISO4217 extends Object implements Validator {

    /**
     * Active Currencies
     *
     * @staticvar array $currencies
     *
     * @link https://en.wikipedia.org/wiki/ISO_4217#Active_codes
     */
    const CURRENCIES = [

        'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG',
        'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND',
        'BOB', 'BOV', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD',
        'CDF', 'CHE', 'CHF', 'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU',
        'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD',
        'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS',
        'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG',
        'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD',
        'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD',
        'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL',
        'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK',
        'MXN', 'MXV', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR',
        'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG',
        'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG',
        'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SVC',
        'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD',
        'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'UYI', 'UYU', 'UZS',
        'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XAG', 'XAU', 'XBA', 'XBB',
        'XBC', 'XBD', 'XCD', 'XDR', 'XOF', 'XPD', 'XPF', 'XPT', 'XSU',
        'XTS', 'XUA', 'XXX', 'YER', 'ZAR', 'ZMW', 'ZWL'
    ];

    // Validator Interface Interface Methods

    /**
     * Validates given Country Currency Code
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $value = $this -> options -> value;

        if( ! is_string( $value ) ) {

            $this -> _error = vsprintf(

                'Validator <strong>%s</strong> expects a string, %s given',

                [
                  $this -> getClass() -> getNamespaceName(), gettype( $value )
                ]
            );

            return FALSE;
        }

        /**
         * @internal
         *
         * Removing non-English characters and modifying input data
         * letter case
         */
        $value = mb_strtoupper(
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

        return ( strlen( $value ) == 3 && in_array( $value, self::CURRENCIES ) );
    }
}
