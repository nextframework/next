<?php

namespace Next\Session;

use Next\Session\Environment\EnvironmentException;    # Session Environment Exception Class
use Next\Components\Object;                           # Object Class

/**
 * Session Environment Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Environment extends Object {

    /**
     * Environment
     *
     * An Environment is a sub-array under $_SESSION to store anything is needed
     *
     * @var string|NULL $environment
     */
    private $environment;

    /**
     * Flag for whether ot not an Environment is locked.
     *
     * Locked Environments are read-only
     *
     * @var boolean $locked
     */
    private $locked = FALSE;

    /**
     * Environment Constructor
     *
     * @param string $environment
     *  Environment name to be registered
     *
     * @param boolean|optional $initializing
     *  Defines whether or not the Session Environment is under initialization,
     *  which will create the required structured under $_SESSION
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the Session Environment
     */
    public function __construct( $environment, $initializing = FALSE, $options = NULL ) {

        parent::__construct( $options );

        // Registering Session Environment

        $this -> registerEnvironment( $environment, $initializing );
    }

    /**
     * Get Environment name
     *
     * @return string
     *  Environment Name
     */
    public function getEnvironment() {
        return $this -> environment;
    }

    // Readability-related Methods

    /**
     * Lock Environment
     *
     * Locked Environments are ready-only
     *
     * @return Next\Session\Environment
     *  Environment Object (Fluent Interface)
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed
     */
    public function lock() {

        if( ! $this -> isDestroyed() ) {
            $this -> locked = TRUE;
        }

        return $this;
    }

    /**
     * Unlock current Environment
     *
     * @return Next\Session\Environment
     *  Environment Object (Fluent Interface)
     *
     * @throws Next\SessionEnvironmentEnvironmentException
     *  Environment has been explicitly destroyed
     */
    public function unlock() {

        if( ! $this -> isDestroyed() ) {
            $this -> locked = FALSE;
        }

        return $this;
    }

    /**
     * Check if current Environment is Locked
     *
     * @return boolean
     *  TRUE if Locked. FALSE otherwise
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed.
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment is locked
     */
    public function isLocked() {

        if( ! $this -> isDestroyed() ) {

            if( $this -> locked !== FALSE ) {
                throw EnvironmentException::locked( $this -> environment );
            }
        }

        return FALSE;
    }

    // Environment Manipulation-related Methods

    /**
     * Destroy an Environment
     *
     * By destroying an Environment all the contents assigned to it is
     * removed from $_SESSION super global array
     *
     * @return Next\Session\Environment
     *  Environment Object (Fluent Interface)
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed
     */
    public function destroy() {

        if( ! $this -> isDestroyed() ) {
            unset( $_SESSION[ $this -> environment ] );
        }

        return $this;
    }

    /**
     * Check if current Environment was Destroyed
     *
     * @return boolean
     *  TRUE if Destroyed. FALSE otherwise
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been destroyed</p>
     */
    public function isDestroyed() {

        if( ! array_key_exists( $this -> environment, $_SESSION ) ) {
            throw EnvironmentException::destroyed( $this -> environment );
        }

        return FALSE;
    }

    // Environment Content Manipulation-related Methods

    /**
     * Unset anything previously assigned to Environment
     *
     * @return Next\Session\Environment
     *  Environment Object (Fluent Interface)
     */
    public function unsetAll() {

        $_SESSION[ $this -> environment ] = array();

        return $this;
    }

    /**
     * Get everything assigned to Environment
     *
     * @return array
     *   $_SESSION slice of current Environment
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed
     */
    public function getAll() {

        if( ! $this -> isDestroyed() ) {
            return $_SESSION[ $this -> environment ];
        }
    }

    /**
     * Append data
     *
     * @param array|string $name
     *
     *   <p>Index to have the new content appended.</p>
     *
     *   <p>
     *       If it doesn't exist, it will be created in the end of
     *       $_SESSION slice
     *   </p>
     *
     * @param mixed|optional $value
     *  Value to be appended
     *
     * @return Next\Session\Environment
     *  Environment Object (Fluent Interface)
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment is Locked
     */
    public function append( $name, $value = NULL ) {

        $this -> isLocked();

        if( is_array( $name ) ) {

            $_SESSION[ $this -> environment ][ key( $name ) ] = array_values( $name );

        } else {

            if( is_array( $value ) ) {

                $_SESSION[ $this -> environment ][ $name ][] = $value;

            } else {

                $_SESSION[ $this -> environment ][ $name ] = $value;
            }
        }

        return $this;
    }

    // OverLoading

    /**
     * Retrieves an Environment index
     *
     * @note Before return, we'll test the PRESENCE of key, not its value
     *
     * @param string $name
     *  Index to be searched in Environment and returned, if exists
     *
     * @return mixed
     *  Desired value from current Environment
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Requested index doesn't exists
     */
    public function __get( $name ) {

        if( ! $this -> isDestroyed() ) {

            $name = (string) $name;

            if( ! array_key_exists( $name, (array) $_SESSION[ $this -> environment ] ) ) {

                throw EnvironmentException::undefinedIndex( $name, $this -> environment );
            }

            return $_SESSION[ $this -> environment ][ $name ];
        }
    }

    /**
     * Add a new index/value to Environment
     *
     * @param string $name
     *  Index to be added to Environment
     *
     * @param mixed $value
     *  Value of the new index
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment is Locked
     */
    public function __set( $name, $value ) {

        if( ! $this -> isLocked() ) {
            $_SESSION[ $this -> environment ][ (string) $name ] = $value;
        }
    }

    /**
     * Checks if given index exists in Environment
     *
     * @note The PRESENCE of key is tested, not its value
     *
     * @param string $name
     *  Index to be searched
     *
     * @return boolean
     *  TRUE if desired argument exists and FALSE otherwise
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed
     */
    public function __isset( $name ) {

        if( ! $this -> isDestroyed() ) {
            return array_key_exists( (string) $name, (array) $_SESSION[ $this -> environment ] );
        }
    }

    /**
     * Unset an Environment index
     *
     * @note Before unset, we'll test the PRESENCE of key, not its value
     *
     * @param string $name
     *  Index to be removed
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment has been explicitly destroyed
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Requested index doesn't exists
     */
    public function __unset( $name ) {

        if( ! $this -> isLocked() ) {

            $name = (string) $name;

            if( ! array_key_exists( $name, (array) $_SESSION[ $this -> environment ] ) ) {
                throw EnvironmentException::undefinedIndex( $name, $this -> environment );
            }

            unset( $_SESSION[ $this -> environment ][ $name ] );
        }
    }

    // Auxiliary Methods

    /**
     * Register Environment
     *
     * Initializes the Session if not started yet and create proper dimension
     * in $_SESSION super global array
     *
     * @param string $environment
     *  Environment name to be registered
     *
     * @param boolean|optional $initializing
     * Defines whether or not the Session Environment is under initializing,
     *  which will create the required structured under $_SESSION
     *
     * @throws Next\Session\Environment\EnvironmentException
     *  Environment name starts with a number
     */
    private function registerEnvironment( $environment, $initializing = FALSE ) {

        if( preg_match( '#(^[0-9])#i', $environment ) ) {

            throw EnvironmentException::invalidEnvironment();
        }

        // Registering Environment

        $this -> environment =& $environment;

        // Initialize Session Environment if not initialized yet

        if( $initializing == TRUE ) {

            try{

                if( $this -> isDestroyed() ) $this -> unsetAll();

            } catch( EnvironmentException $e ) {

                $this -> unsetAll();
            }
        }
    }
}
