<?php

namespace Next\HTTP\Headers\Fields;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Validate\Validate;                        # Validate Interface
use Next\Validate\HTTP\Headers\Headers;            # Headers Validator Interface
use Next\Components\Object;                        # Object Class
use Next\Components\Parameter;                     # Parameter Class

/**
 * Header Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractField extends Object implements Parameterizable, Field {

    /**
     * Headers Fields Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = array(

        /**
         * Defines whether or not Headers accept multiple values at same time
         * Defaults to FALSE
         */
        'acceptMultiples'    => FALSE,

        /**
         * Defines the separator in order to split the input string
         * Defaluts to "," (comma)
         */
        'multiplesSeparator' => ',',

        /**
         * Defines whether or not whitespaces should preserved before validation
         * Defaults to FALSE
         */
        'preserveWhitespace' => FALSE
    );

    /**
     * Header Value
     *
     * @var string $value
     */
    protected $value;

    /**
     * Header Field Constructor
     *
     * @param string $value
     *  Header Value
     */
    protected function init() {

        // Checking Integrity

        $this -> checkIntegrity();
    }

    // Header Field Interface Methods Implementation

    /**
     * Set Header Value
     *
     *  - Prepare it by cleaning and/or apply pre-verification routines
     *
     *  - Check it, using specific validators which follows one or more RFC specification(s)
     *
     *  - Adjust it by apply a post-validation callback over it
     *
     * @param string $value
     *  Header Value
     *
     * @throws Next\HTTP\Headers\Fields\FieldsException
     *  All possible values assigned to the Header are invalid
     */
    public function setValue( $value ) {

        // Removing Header's Name from value, if present

        $value = preg_replace( sprintf( '/%s:?/', $this -> options -> name ), '', $value );

        /**
         * @internal
         * Will the header need whitespaces?
         *
         * Most of Header's Fields does not need any whitespace, but some, like Date, does.
         * So, before remove them, we have to check if they are required, or not
         */
        if( $this -> options -> preserveWhitespace === FALSE ) {

            $value = preg_replace( '/\s{2,}/', ' ', $value );
        }

        // Removing all remaining boundary whitespaces

        $value = trim( $value );

        // Executing Routines BEFORE Validation

        $value = $this -> preCheck( $value );

        //--------------------------

        $valid = array();

        // The Header accepts multiple values?

        /**
         * @internal
         *
         * Test if this separation is REALLY necessary and,
         * if not, use just one foreach, outside the IF
         */
        if( $this -> options -> acceptMultiples !== FALSE &&
            strpos( $value, $this -> options -> multiplesSeparator ) !== FALSE ) {

            // Splitting and cleaning it

            $value = array_map(

                 'trim',

                 array_filter(

                     explode( $this -> options -> multiplesSeparator, $value )
                 )
             );

            if( count( $value ) != 0 ) {

                $validator = $this -> getValidator()
                                   -> setOptions( array( 'value' => trim( $v ) ) );

                foreach( $value as $v ) {

                    if( $validator -> validate() !== FALSE ) {

                        // Adding value, AFTER applied POST Validations Routines

                        $valid[] = $this -> postCheck( $v );
                    }
                }
            }

        } else {

            // If the Header does not accepts multiple values, let's validate just once

            $validator = $this -> getValidator()
                               -> setOptions( array( 'value' => $value ) );

            if( $validator -> validate() !== FALSE ) {

                // Adding value, AFTER applied POST Validations Routines

                $valid[] = $this -> postCheck( $value );
            }
        }

        // Do we have at least one valid value?

        if( count( $valid ) == 0 ) {
            throw FieldsException::invalidHeaderValue( $this -> options -> name );
        }

        // Building Header

        $this -> value = implode( sprintf( '%s ', $this -> options -> multiplesSeparator ), $valid );
    }

    /**
     * Get Header Name
     *
     * Header Name comes from Field Options instead of from string representation
     * of Object Class not only because some Fields have hyphens in its name and
     * this character is not allowed as PHP Class Name Definition,
     * but because we're already overwriting this method to return the full string
     * representation of Header Field
     *
     * @return string
     *  Header Field Name
     */
    public function getName() {
        return $this -> options -> name;
    }

    /**
     * Get Header Value
     *
     * @return string
     *  Header Field Value
     */
    public function getValue() {
        return $this -> value;
    }

    // Auxiliary Methods

    /**
     * Check Options Integrity
     *
     * @throws Next\HTTP\Headers\Fields\FieldsException
     *  Header Field has no well-formed name
     *
     * @throws Next\HTTP\Headers\Fields\FieldsException
     *  Object defined as validator is not instance of Nex\Validate\Validate
     *
     * @throws Next\HTTP\Headers\Fields\FieldsException
     *  Provided validator is not a Header Field Validator, characterized
     *  as instance of Nex\Validate\HTTP\Headers\Headers
     */
    private function checkIntegrity() {

        // Headers Options

        if( ! isset( $this -> options -> name ) || empty( $this -> options -> name ) ) {

            throw FieldsException::unfullfilledRequirements( 'Headers must have a name!' );
        }

        // Validator Interfaces Implementations

        $validator = $this -> getValidator();

        if( ! $validator instanceof Validate ) {

            throw FieldsException::unfullfilledRequirements(
                'Header Fields Validators must implement Validate Interface'
            );
        }

        if( ! $validator instanceof Headers ) {

            throw FieldsException::unfullfilledRequirements(
                'HTTP Headers Validators must implement HTTP Headers Validate Interface'
            );
        }
    }

    /**
     * Pre-Check Routines
     *
     * Before validation, some Headers can receive some treatment before they can
     * be validated, changing input data or facilitating the validation process
     *
     * Not all Header Fields need it, so, by default, the input data are outputted "as is"
     *
     * @param string $data
     *  Data to manipulate before validation
     *
     * @return string
     *  Input Data
     */
    protected function preCheck( $data ) {
        return $data;
    }

    /**
     * Post-Check Routines
     *
     * After validation, some Headers can receive some treatment before effectively
     * be added as a Header
     *
     * Not all Header Fields need it, so, by default, the input data are outputted "as is"
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string
     *  Input Data
     */
    protected function postCheck( $data ) {
        return $data;
    }

    // Abstract Methods Definition

    /**
     * Get Header Field Validator
     *
     * It's Abstract because each Header Field requires a different validation routine
     */
    abstract protected function getValidator();

    // OverLoading

    /**
     * Return the full string representation of Header Field
     */
    public function __toString() {
        return $this -> options -> name;
    }
}
