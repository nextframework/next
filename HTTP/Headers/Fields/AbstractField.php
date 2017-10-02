<?php

/**
 * HTTP Header Field Abstract Class | HTTP\Headers\Fields\AbstractField.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validate\Validator;    # Validator Interface
use Next\Components\Object;     # Object Class

/**
 * Header Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractField extends Object implements Field {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

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
        'preserveWhitespace' => FALSE,
    ];

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

        $this -> setValue( $this -> options -> value );
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
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
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

        $valid = [];

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

                foreach( $value as $v ) {

                    $validator = $this -> getValidator( trim( $v ) );

                    if( $validator -> validate() !== FALSE ) {

                        // Adding value, AFTER applied POST Validations Routines

                        $valid[] = $this -> postCheck( $v );
                    }
                }
            }

        } else {

            // If the Header does not accepts multiple values, let's validate just once

            $validator = $this -> getValidator( $value );

            if( $validator -> validate() !== FALSE ) {

                // Adding value, AFTER applied POST Validations Routines

                $valid[] = $this -> postCheck( $value );
            }
        }

        // Do we have at least one valid value?

        if( count( $valid ) == 0 ) {

            throw new InvalidArgumentException(

                sprintf(

                     'All values assigned to <strong>%s</strong> Header are invalid',

                     $this -> options -> name
                )
            );
        }

        // Building Header

        $this -> value = implode(
            sprintf( '%s ', $this -> options -> multiplesSeparator ), $valid
        );
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
     * Get Header Value after treated and validated
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
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Header Field has no well-formed name
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Object defined as validator is not instance of \Next\Validate\Validator
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Provided validator is not a Header Field Validator, characterized
     *  as instance of \Next\Validate\HTTP\Headers\Headers
     */
    private function checkIntegrity() {

        // Headers Options

        if( ! isset( $this -> options -> name ) || empty( $this -> options -> name ) ) {

            throw new InvalidArgumentException(
                sprintf( 'Header <strong>%s</strong> doesn\'t have a defined name', (string) $this )
            );
        }

        if( ! isset( $this -> options -> value ) || empty( $this -> options -> value ) ) {

            throw new InvalidArgumentException(

                sprintf(

                    'Header <strong>%s</strong> doesn\'t have a value to be set',

                    $this -> options -> name
                )
            );
        }

        // Validator Interfaces Implementations

        /**
         * @internal
         *
         * AbstractField::getValidator() requires a value to to be used as
         * Validator Constructor but here, before all the routines of adding
         * the Header Field start, we just need to check the Validator iteself,
         * so we use a dummy value just to get access of the object provided
         */
        $validator = $this -> getValidator( NULL );

        if( ! $validator instanceof Validator ) {

            throw new InvalidArgumentException(
                'Header Fields Validators must implement <em>Next\Validate\Validator</em> Interface'
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

    // Abstract Method Definition

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @abstract
     */
    abstract protected function getValidator( $value );

    // OverLoading

    /**
     * Return the full string representation of Header Field
     */
    public function __toString() {
        return $this -> options -> name;
    }
}
