<?php

/**
 * Debug Component Error Exception Standard Class | Components\Debug\ErrorException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Debug;

/**
 * Defines a variation our own Exception Class with focused on triggered errors
 *
 * @package    Next\Components\Debug
 */
class ErrorException extends Exception {

    /**
     * ErrorException Constructor
     *
     * @param string $message
     *  Exception Message
     *
     * @param integer $severity
     *  Exception Severity
     *
     * @param string $file
     *  Filename were the error occurred
     *
     * @param integer $line
     *  Line were the error occurred
     */
    public function __construct( $message, $severity, $file, $line ) {

        parent::__construct( $message, parent::PHP_ERROR );

        $this -> severity =& $severity;

        // Changing the File and Line where the Error resides

        $this -> file =& $file;
        $this -> line =& $line;
    }

    /**
     * Gets the exception severity
     *
     * @return integer
     *  Returns the severity level of the exception
     */
    public function getSeverity() {
        return $this -> severity;
    }

    /**
     *  Translate Severity Code to a nice (and more descriptive) text
     *
     * @return string
     *  Severity Code translated
     */
    public function __toString() {

        switch( $this -> severity ) {

            case E_ERROR:                           # 1
                return 'Fatal Error';
            case E_WARNING:                         # 2
                return 'Warning';
            case E_PARSE:                           # 4
                return 'Parse Error';
            case E_NOTICE:                          # 8
                return 'Notice';
            case E_CORE_ERROR:                      # 16
                return 'Fatal Error';
            case E_CORE_WARNING:                    # 32
                return 'Warning';
            case E_COMPILE_ERROR:                   # 64
                return 'Fatal Error';
            case E_COMPILE_WARNING:                 # 128
                return 'Warning';
            case E_USER_ERROR:                      # 256
                return 'Fatal Error';
            case E_USER_WARNING:                    # 512
                return 'Warning';
            case E_USER_NOTICE:                     # 1024
                return 'Notice';
            case E_STRICT:                          # 2048
                return 'Strict Standards Error';
            case E_RECOVERABLE_ERROR:               # 4096
                return 'Catchable Fatal Error';
            case E_DEPRECATED:                      # 8192
                return 'Deprecated Error';
            case E_USER_DEPRECATED:                 # 16384
                return 'Deprecated Error';
            default:
                return '';
        }
    }
}