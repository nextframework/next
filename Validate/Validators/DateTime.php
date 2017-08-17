<?php

namespace Next\Validate\Validators;

use Next\Validate\Validator;
use Next\Components\Object;

class DateTime extends Object implements Validator {

    // Validator Interface Method Implementation

    /**
     * Validates a WTC Compliant DateTime
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @see https://www.w3.org/TR/NOTE-datetime
     * @see http://php.net/manual/en/datetime.createfromformat.php
     */
    public function validate() {

        /**
         * @internal
         *
         * W3C DateTime Formats slightly expanded regarding timezones
         * and converted to PHP's DateTime Parameter string format
         *
         * @see http://php.net/manual/en/datetime.createfromformat.php
         */
        $formats = array(

            'Y',        // Year only. E.g. 1997
            'Y-m',      // Year and month. E.g. 1997-07
            'Y-m-d',    // Complete Date. E.g. 1997-07-16

            /**
             * @internal
             *
             * Complete date plus hours and minutes WITHOUT Timezone
             * E.g. 1997-07-16T19:20
             */
            'Y-m-d\TH:i',

            /**
             * @internal
             *
             * Complete date plus hours and minutes WITH Timezone
             * E.g. 1997-07-16T19:20+01:00
             */
            'Y-m-d\TH:iT',

            /**
             * @internal
             *
             * Complete date plus hours, minutes and seconds WITHOUT Timezone
             * E.g. 1997-07-16T19:20:30
             */
            'Y-m-d\TH:i:s',

            /**
             * @internal
             *
             * Complete date plus hours, minutes and seconds WITH Timezone
             * E.g. 1997-07-16T19:20:30+01:00
             */
            'Y-m-d\TH:i:sT',

            /**
             * @internal
             *
             * Complete date plus hours, minutes, seconds and a
             * decimal fraction of a second WITHOUT Timezone
             *
             * E.g. 1997-07-16T19:20:30.45
             */
            'Y-m-d\TH:i:s.u',

            /**
             * @internal
             *
             * Complete date plus hours, minutes, seconds and a
             * decimal fraction of a second WITH Timezone
             *
             * E.g. 1997-07-16T19:20:30.45+01:00
             */
            'Y-m-d\TH:i:s.uT'
        );

        foreach( $formats as $format ) {

            $dateObj = \DateTime::createFromFormat( $format, $this -> options -> value );

            if( $dateObj !== FALSE ) return TRUE;
        }

        return FALSE;
    }
}