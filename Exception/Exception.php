<?php

/**
 * Extended Exception Class | Exception\Exception.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception;

require_once __DIR__ . '/../Validation/Verifiable.php';

use Next\Validation\Verifiable;           # Verifiable Interface Class
use Next\Validation\HTTP\Headers\Code;    # HTTP Status Code Validator Class

/**
 * Defines a variation of native Exception Class with native support
 * for HTTP Response Codes and, in the future, translation
 *
 * @package    Next\Exception
 *
 * @uses       Next\Components\InterfacesVerifiable
 *             Next\Validation\HTTP\Headers\Code
 *             \Exception
 */
class Exception extends \Exception implements Verifiable {

    // Base Exception Codes

    /**
     * Unknown or PHP Internal Exception Code
     *
     * @var integer
     */
    const UNKNOWN                      = 0;

    /**
     * PHP-Error
     *
     * @var integer
     */
    const PHP_ERROR                    = 1;

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
         * throw new Exception( 'Some message', Exception::UNKNOWN, NULL, 'myCallbackFunction' );
         * ````
         *
         * Then `$this -> responseCode` WILL NOT have the default
         * value 500 because it was explicitly set as NULL
         */
        list( $this -> code, $this -> responseCode, $this -> callback, $this -> file, $this -> line, $this -> severity ) =
            $args + [ self::UNKNOWN, 500, [], $this -> getFile(), $this -> getLine(), $this -> getSeverity() ];

        // Verifying Object Integrity

        $this -> verify();

        // Constructing the Exception

        parent::__construct( $message, $this -> code );
    }

    // Accessory Methods

    /**
     * Gets the exception severity
     *
     * @return integer|void
     *  Returns the severity level of the Exception, if any
     */
    public function getSeverity() :? int {
        return $this -> severity;
    }

    /**
     * Get defined HTTP Response Code
     *
     * @return integer
     *  HTTP Response Code, if any, associated to Exception thrown
     */
    public function getResponseCode() :? int {
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
     * since the Exception Code can be virtually anything, we'll only
     * check if the HTTP Response Code provided is valid
     *
     * We'll always provide a default code, of course, but when manually
     * defined, let's make sure it's valid in order to not send an
     * invalid Response Header.
     *
     * Also, even being possible to throw Exceptions inside the
     * Exception Class, we avoid this practice in order to protect
     * against a infinite loop.
     *
     * We could trigger an error, but the most appropriate Error Level
     * to be used (E_ERROR) cannot be used
     *
     * And since E_USER_ERROR can be, accidentally or not, hidden with a
     * very low error_reporting() definition, we'll avoid it too.
     *
     * So, using die(), it's not elegant, but no one can complain ^^
     */
    public function verify() : void {

        /**
         * @internal
         *
         * We're ignoring the HTTP Status Code validation if the class doesn't
         * exist in order to avoid errors when an Exception is thrown within
         * the Loader module
         *
         * Technically, the right way to do this would be manually require,
         * for example, the Object Class in Next/Validation/HTTP/Headers/Code.php
         * but the Object Class has its own dependencies which have more
         * dependencies so, no, thank you
         *
         * The HTTP Status Code isn't too important in that moment anyway...
         */
        if( ! class_exists( 'Next\Validation\HTTP\Headers\Code' ) ) return;

        $validator = new Code( [ 'value' => $this -> responseCode ] );

        if( ! $validator -> validate() ) {
            die( 'Invalid or unknown HTTP Response Code supplied' );
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
    public function __toString() : string {
        return $this -> getMessage();
    }
}
