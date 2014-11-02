<?php

namespace Next\Components\Debug;

use Next\Components\Decorators\Chain;                # Decorators Chain Class
use Next\Components\Decorators\LinkifyDecorator;     # Linkify Decorator

/**
 * Exception Class
 *
 * A Standard Class which allows Exception to be Translated
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Exception extends \Exception {

    /**
     * Exception Code Range, overwritten in child classes
     *
     * @var array $range
     */
    protected $range = array( 0x00000000, 0x00000032 );

    // Base Exception Codes

    /**
     * Unknown or PHP Internal Exception Code
     *
     * @var integer
     */
    const UNKNOWN                      = 0x00000000;

    /**
     * Supplied Exception Code is Out of Range
     *
     * @var integer
     */
    const OUT_OF_RANGE                 = 0x00000001;

    /**
     * Too few arguments for Exception Message Placeholders
     *
     * @var integer
     */
    const TOO_FEW_ARGUMENTS            = 0x00000002;

    /**
     * Supplied HTTP Response Code is not valid
     *
     * @var integer
     */
    const INVALID_RESPONSE_CODE        = 0x00000003;

    /**
     * PHP-Error
     *
     * @var integer
     */
    const PHP_ERROR                    = 0x00000004;

    /**
     * Unfulfilled Requirements
     *
     * @var integer
     */
    const UNFULFILLED_REQUIREMENTS     = 0x00000005;

    /**
     * Usage Failure
     *
     * @var integer
     */
    const WRONG_USE                    = 0x00000006;

    /**
     * Usage Failure
     *
     * @var integer
     */
    const LOGIC_ERROR                  = 0x00000006;

    /**
     * Placeholders Replacements
     *
     * @var array $replacements
     */
    private $replacements = array();

    /**
     * HTTP Response Code
     *
     * @var integer $responseCode
     */
    private $responseCode = 200;

    /**
     * Exception Constructor
     */
    public function __construct() {

        $args = func_get_args();

        // Exception Message

        $message = array_shift( $args );

        // Listing Additional Exception Components

        list( $code, $replacements, $responseCode ) =
            $args + array( self::UNKNOWN, array(), 200 );

        // Checking Exception Components...

        $this -> checkComponents( $message, (array) $replacements, $code, $responseCode );

        // ... and settle them up

        $this -> replacements = (array) $replacements;

        $this -> responseCode = $responseCode;

        // Constructing the Exception

        parent::__construct( $this -> _getMessage( $message ), $code );
    }

    // Common Exceptions Messages

    /**
     * Unfulfilled Requirements
     *
     * Something is going wrong because your server did not achieved
     * the minimum requirements defined by desired Module
     *
     * @param string $message
     *  Message to be thrown
     *
     * @param array|optional $args
     *  Variable list of argument to build final message
     *
     * @return Next\Components\Debug
     *  Exception for Unfulfilled requirements
     */
    public static function unfullfilledRequirements( $message, array $args = array() ) {
        return new static( $message, self::UNFULFILLED_REQUIREMENTS, $args );
    }

    /**
     * Logic Violations
     *
     * Something is going wrong because of a Logic Violation.
     * Logic Violation's Exceptions are thrown basically when your actions makes no sense :P
     *
     * @param string $message
     *  Message to be thrown
     *
     * @param array|optional $args
     *  Variable list of argument to build final message
     *
     * @return Next\Components\Debug
     *  Exception for Unfulfilled requirements
     */
    public static function logic( $message, array $args = array() ) {
        return new static( $message, self::LOGIC_ERROR, $args );
    }

    /**
     * Wrong Use of Resources
     *
     * Something is going wrong after using a Framework Feature
     *
     * @param string $message
     *  Message to be thrown
     *
     * @param array|optional $args
     *  Variable list of argument to build final message
     *
     * @return Next\Components\Debug
     *  Exception for Unfulfilled requirements
     */
    public static function wrongUse( $message, array $args = array() ) {
        return new static( $message, self::WRONG_USE, $args );
    }

    // Accessors

    /**
     * Get defined HTTP Response Code
     *
     * @return integer
     *  HTTP Response Code, if any, associated to Exception thrown
     */
    public function getResponseCode() {
        return $this -> responseCode;
    }

    // OverLoading

    /**
     * Translates Exception Message
     *
     * Replace Exception Message placeholders and, optionally,
     * translate it to another language
     *
     * This is a wrapper method of Exception::getMessage() to allow
     * the translated message be returned when casting Exception Object
     * to string
     *
     * @return string
     *  Exception Message
     */
    public function __toString() {
        return $this -> getMessage();
    }

    // Auxiliary Methods

    /**
     * Translates Exception Message
     *
     * Decorates Exception Message and replaces its placeholders
     *
     * We could do this inside Exception::getMessage(), but it's final :(
     *
     * @param string $message
     *  Exception Message
     *
     * @return string
     *  Translated Exception Message
     */
    private function _getMessage( $message ) {

        $decoratorsChain = new Chain;

        $decoratorsChain -> add( new LinkifyDecorator( $message ) );

        iterator_apply(

            $decoratorsChain -> getIterator(),

            function( \Iterator $decorator ) use ( &$message ) {

                $message = $decorator -> current() -> decorate() -> getResource();

                return TRUE;
            },

            array( $decoratorsChain -> getIterator() )
        );

        return vsprintf( $message, $this -> replacements );
    }

    /**
     * Check Exception Components
     *
     * An Exception can be formed by one and up to four components:
     *
     * - Message
     * - Message Placeholders Replacements
     * - Exception Code
     * - HTTP Response Code
     *
     * But the only component required is the Exception Message, so if one or
     * more of other components are present, we have to check in order to
     * make sure they are valid.
     *
     * These checks includes:
     *
     * - Make sure the number of Message Placeholders fits the number
     *  of Placeholders Replacements provided
     *
     * We'll always provide an Exception Code, in case none is specified,
     * but when specified, we'll compare it within the range provided in 'range'
     * property, overwritten in child classes
     *
     * - Check the HTTP Response Code provided
     *
     * We'll always provide a default code too, but when manually defined it must
     * be checked in order to not send a invalid Response Header.
     *
     * Even being possible to throw Exceptions inside the Exception Class, we avoid this
     * practice in order to protect against a infinite loop.
     *
     * We could trigger an error, but the most appropriate Error Level to be used
     * (E_ERROR) cannot be used
     *
     * And since E_USER_ERROR can be, accidentally or not, hidden with a
     * very low error_reporting() definition, we'll avoid it too.
     *
     * So, using die(), it's not very elegant, but no one can complain ^^
     *
     * @param string $message
     *  Exception Message
     *
     * @param array $replacements
     *
     *  Placeholders Replacements.
     *  Must match the number o Placeholders in Exception Message
     *
     * @param integer $code
     *  Exception Code
     *
     * @param integer $responseCode
     *  HTTP response Code
     */
    private function checkComponents( $message, array $replacements, $code, $responseCode ) {

        $classname = basename( get_class( $this ) );

        // Exception Message Placeholders

        preg_match_all( '/(\%[0-9\.]{0,}[bcdeEufFgGsxX])/', $message, $placeholders );

        if( count( $placeholders ) > 0 && count( $placeholders[ 1 ] ) > count( $replacements ) ) {

            die(
                sprintf(

                    '[0x%08X]: Too few arguments supplied as Message Placeholders in <strong>%s</strong> Exception Class',

                    self::TOO_FEW_ARGUMENTS, $classname
                )
            );
        }

        // Exception Code Range

        if( $code > 0x00000032 ) {

            list( $min, $max ) = $this -> range + array( 0x00000000, 0x00000032 );

            if( $code < $min || $code > $max ) {

                die(

                    vsprintf(

                        '[0x%08X]: Exception Code <strong>0x%08X</strong> is Out of <strong>%s</strong> Range',

                        array( self::OUT_OF_RANGE, $code, $classname )
                    )
                );
            }
        }

        // Response Code

        $codes = '(
          10[012]|                                                   # [ Informational 1xx ]
          2(0[0-8]|26)|                                              # [ Successful 2xx ]
          30[0-8]|                                                   # [ Redirection 3xx ]
          4(0[0-9]|1[1-8]|2[02345689]|31|4[49]|5[01]|9[1456789])|    # [ Client Error 4xx ]
          5(0[0-9]|1[01]|9[89])                                      # [ Server Error 5xx ]
        )';

        if( preg_match( sprintf( '/%s/x', $codes ), $responseCode ) == 0 ) {

            die(

                sprintf(

                    '[0x%08X]: Invalid or unknown HTTP Response Code supplied',

                    self::INVALID_RESPONSE_CODE
                )
            );
        }
    }
}
