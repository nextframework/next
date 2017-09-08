<?php

/**
 * Extended Exception Class | Debug\Exception\Exception.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Debug\Exception;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface Class
use Next\Validate\HTTP\Headers\Code;          # HTTP Status Code Validator Class

/**
 * Defines a variation of native Exception Class with native support
 * for placeholded messages and, in the future, translation
 *
 * @package    Next\Debug
 *
 * @uses       \Exception
 */
class Exception extends \Exception implements Verifiable {

    // Base Exception Codes

    /**
     * Unknown or PHP Internal Exception Code
     *
     * @var integer
     */
    const UNKNOWN                      = 0x00000000;

    /**
     * PHP-Error
     *
     * @var integer
     */
    const PHP_ERROR                    = 0x00000001;

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
     * Unfulfilled Requirements
     *
     * @var integer
     */
    const UNFULFILLED_REQUIREMENTS     = 0x00000004;

    /**
     * Fatal Error on resource usage
     *
     * @var integer
     */
    const WRONG_USE                    = 0x00000005;

    /**
     * Logical Error on resource usage
     *
     * @var integer
     */
    const LOGIC_ERROR                  = 0x00000006;

    /**
     * Placeholders Replacements
     *
     * @var array $replacements
     */
    protected $replacements = [];

    /**
     * Default HTTP Response Code
     *
     * @var integer $responseCode
     */
    protected $responseCode = 500;

    /**
     * Error Severity
     *
     * @var integer $severity
     */
    protected $severity;

    /**
     * An optional callback to associate with the Exception thrown
     * as complement of Error Standardization Concept
     *
     * @var callable|optional
     */
    protected $callback;

    /**
     * Exception Constructor
     */
    public function __construct() {

        $args = func_get_args();

        // Exception Message

        $message = array_shift( $args );

        // Listing Additional Exception Components

        /*extract(

            // @todo Watch this index!
            ( isset( $args[ 0 ] ) ? $args[ 0 ] : $args ) + [
              'code'         => self::UNKNOWN,
              'replacements' => [],
              'responseCode' => 500,
              'callback'     => [],
              'file'         => $this -> getFile(),
              'line'         => $this -> getLine(),
              'severity'     => ''
            ]
        );*/

        /**
         * @todo Provide default values for skipped parameters
         *
         * If func_get_args() is shorter than the number of variables
         * being created with list() the assignment below works.
         *
         * But if one or more parameters are set as NULL to "skip"
         * their positions in order to assign something some positions
         * ahead, e.g:
         *
         * ````
         * throw new Exception( 'Some message', Exception::WRONG_USE, NULL, 'myCallbackFunction' );
         * ````
         *
         * Then `$this -> responseCode` WILL NOT have the default
         * value 500 because it was explicitly set as NULL
         */
        list( $this -> code, $this -> responseCode, $this -> callback, $this -> file, $this -> line, $this -> severity ) =
            $args + array( self::UNKNOWN, 500, array(), $this -> getFile(), $this -> getLine(), $this -> getSeverity() );

        // Verifying Exception Object Integrity

        $this -> verify();

        // Constructing the Exception

        parent::__construct( $message, $this -> code );
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
     * @return \Next\Debug
     *  Exception for Unfulfilled requirements
     */
    public static function unfullfilledRequirements( $message, array $args = [] ) {
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
     * @return \Next\Debug
     *  Exception for Unfulfilled requirements
     */
    public static function logic( $message, array $args = [] ) {
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
     * @return \Next\Debug
     *  Exception for Unfulfilled requirements
     */
    public static function wrongUse( $message, array $args = [] ) {
        return new static( $message, self::WRONG_USE, $args );
    }

    // Accessory Methods

    /**
     * Gets the exception severity
     *
     * @return integer
     *  Returns the severity level of the Exception
     */
    public function getSeverity() {
        return $this -> severity;
    }

    /**
     * Get defined HTTP Response Code
     *
     * @return integer
     *  HTTP Response Code, if any, associated to Exception thrown
     */
    public function getResponseCode() {
        return $this -> responseCode;
    }

    /**
     * Get associated Exception callback
     *
     * @return callable|void
     *  Exception Callback, if provided
     */
    public function getCallback() {
        return $this -> callback;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verify Exception Object Integrity.
     *
     * An Exception can be formed by one and up to three components:
     *
     * - Message
     * - Exception Code
     * - HTTP Response Code
     *
     * But the only component required is the Exception Message and
     * Exception Code can be virtually anything we'll check if the
     * HTTP Response Code provided is valid
     *
     * We'll always provide a default code, of course, but when manually
     * defined  let's make sure it's valid in order to not send an
     * invalid Response Header.
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
     */
    public function verify() {

        $validator = new Code( [ 'value' => $this -> responseCode ] );

        if( ! $validator -> validate() ) {

            die(

                sprintf(

                    '[0x%08X]: Invalid or unknown HTTP Response Code supplied',

                    self::INVALID_RESPONSE_CODE
                )
            );
        }
    }

    // OverLoading

    /**
     * Wrapper method of Exception::getMessage() to allow
     * the translated message be returned when casting Exception Object
     * to string
     *
     * @return string
     *  Exception Message
     */
    public function __toString() {
        return $this -> getMessage();
    }
}
