<?php

/**
 * Sessions Environment Class | Session\Environment.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Session;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\AccessViolationException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;    # Object Class

/**
 * The Session Environment manipulates one specific slice of $_SESSION with a
 * few additional benefits like locking it to prevent accidental overwriting
 *
 * @package    Next\Session
 *
 * @uses       Next\Exception\Exceptions\AccessViolationException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object
 */
class Environment extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'name'         => [ 'required' => TRUE ],
        'initializing' => [ 'required' => FALSE, 'default' => FALSE ]
    ];

    /**
     * Environment Name.
     * An Environment is a dimension under $_SESSION to store anything
     * is needed
     *
     * @var string $environment
     */
    private $environment;

    /**
     * Flag for whether not not an Environment is locked.
     *
     * Locked Environments are read-only
     *
     * @var boolean $locked
     */
    private $locked = FALSE;

    /**
     * Additional Initialization.
     * Registers a new Session Environment, creating a new dimension
     * under $_SESSION, optionally, emptying it
     */
    protected function init() : void {
        $this -> registerEnvironment();
    }

    /**
     * Get Environment name
     *
     * @return string
     *  Environment Name
     */
    public function getEnvironment() :? string{
        return $this -> environment;
    }

    // Readability-related Methods

    /**
     * Lock Environment
     *
     * Locked Environments are ready-only
     *
     * @return \Next\Session\Environment
     *  Environment Object (Fluent Interface)
     */
    public function lock() : Environment {

        if( ! $this -> isDestroyed() ) {
            $this -> locked = TRUE;
        }

        return $this;
    }

    /**
     * Unlock current Environment
     *
     * @return \Next\Session\Environment
     *  Environment Object (Fluent Interface)
     */
    public function unlock() : Environment {

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
     */
    public function isLocked() : bool {
        return ( ! $this -> isDestroyed() && $this -> locked !== FALSE );
    }

    // Environment Manipulation-related Methods

    /**
     * Destroy an Environment
     *
     * By destroying an Environment all the contents assigned to it is
     * removed from $_SESSION super global array
     *
     * @return \Next\Session\Environment
     *  Environment Object (Fluent Interface)
     */
    public function destroy() : Environment {

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
     */
    public function isDestroyed() : bool {
        return ( ! array_key_exists( $this -> environment, $_SESSION ) );
    }

    // Environment Content Manipulation-related Methods

    /**
     * Unset anything previously assigned to Environment
     *
     * @return \Next\Session\Environment
     *  Environment Object (Fluent Interface)
     */
    public function unsetAll() : Environment {

        $_SESSION[ $this -> environment ] = [];

        return $this;
    }

    /**
     * Get everything assigned to Environment
     *
     * @return array|NULL
     *   Return the $_SESSION slice corresponding to the current Environment
     *   if it hasn't been explicitly destroyed, otherwise returns NULL
     */
    public function getAll() :? array {

        if( ! $this -> isDestroyed() ) {
            return $_SESSION[ $this -> environment ];
        }

        return NULL;
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
     * @return \Next\Session\Environment
     *  Environment Object (Fluent Interface)
     */
    public function append( $name, $value = NULL ) : Environment {

        $this -> isLocked();

        if( (array) $name === $name ) {

            $_SESSION[ $this -> environment ][ key( $name ) ] = array_values( $name );

        } else {

            if( (array) $value === $value ) {

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
     *  Desired value from current Environment if it hasn't
     *  been explicitly destroyed, otherwise nothing is returned
     *
     * @throws \Next\Exception\Exceptions\AccessViolationException
     *  Thrown if requested offset doesn't exists under
     *  initialized Session Environment
     */
    public function __get( $name ) {

        if( ! $this -> isDestroyed() ) {

            if( ! array_key_exists( $name, (array) $_SESSION[ $this -> environment ] ) ) {

                throw new AccessViolationException(

                    sprintf(

                        'Undefined index <strong>%s</strong> in Environment <strong>%s</strong>',

                        $name, $this -> environment
                    )
                );
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
     */
    public function __set( $name, $value ) : void {

        if( ! $this -> isLocked() ) {
            $_SESSION[ $this -> environment ][ $name ] = $value;
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
     *  TRUE if desired argument exists and FALSE otherwise.
     *  If Environment has been explicitly destroyed return FALSE as well
     */
    public function __isset( $name ) : bool {

        if( ! $this -> isDestroyed() ) {
            return array_key_exists( $name, (array) $_SESSION[ $this -> environment ] );
        }

        return FALSE;
    }

    /**
     * Unset an Environment index
     *
     * @note Before unset, we'll test the PRESENCE of key, not its value
     *
     * @param string $name
     *  Index to be removed
     */
    public function __unset( $name ) : void {

        if( ! $this -> isLocked() ) {

            if( array_key_exists( $name, (array) $_SESSION[ $this -> environment ] ) ) {
                unset( $_SESSION[ $this -> environment ][ $name ] );
            }
        }
    }

    // Auxiliary Methods

    /**
     * Registers a new Environment.
     * Registers a new Session Environment, creating a new dimension
     * under $_SESSION, optionally, emptying it
     */
    private function registerEnvironment(): void {

        if( preg_match( '#(^[0-9])#i', $this -> options -> environment ) ) {

            throw new InvalidArgumentException(
                'Session environment must not start with a number'
            );
        }

        // Registering Environment

        $this -> environment = $this -> options -> environment;

        // Initialize Session Environment if not initialized yet

        if( $this -> options -> initializing !== FALSE || $this -> isDestroyed() ) {
            $this -> unsetAll();
        }
    }
}
