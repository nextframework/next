<?php

/**
 * HTTP Entity Header Field Class: Content-Language | HTTP\Headers\Fields\Entity\ContentLanguage.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\HTTP\Headers\Fields\Entity;

use Next\HTTP\Headers\Fields\Entity;           # Entity Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * Entity 'Content-Language' Header Field Validator Class
 */
use Next\Validate\HTTP\Headers\Entity\ContentLanguage as Validator;

/**
 * 'Content-Language' Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentLanguage extends AbstractField implements Entity {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Fix Language Abbreviation and/or Country Code cases
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) {

        return preg_replace_callback(

        '@^(.*?)(-.*?)?(;q=.*?)?$@',

            function( array $matches ) {

                // Language Abbreviations must be lowercase

                $return = strtolower( $matches[ 1 ] );

                // Country Codes must be upercase

                if( isset( $matches[ 2 ] ) ) {
                    $return .= strtoupper( $matches[ 2 ] );
                }

                return $return;
            },

            $data
        );
    }

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validate\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Validator( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Accept-Language', 'acceptMultiples' => TRUE );
    }
}
