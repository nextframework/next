<?php

/**
 * Sessions Manager Class | Session\Manager.php
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
use Next\Exception\Exceptions\RuntimeException;

/**
 * Defines the Session Manager Class handling everything, from the
 * Session initialization to its destruction, including
 * with custom Session Handlers
 *
 * @package    Next\Session
 */
class Manager {

    /**
     * Session Instance
     *
     * @var Session $instance
     */
    protected static $instance;

    /**
     * Session Save Path
     *
     * @var string $savePath
     */
    protected $savePath;

    /**
     * Session Max Lifetime
     *
     * @staticvar integer $lifetime
     */
    protected static $lifetime = 180;

    /**
     * Session Handlers Manager
     *
     * @var \Next\Session\Handlers $handlers
     */
    protected $handlers;

    /**
     * Default Session Environment
     *
     * @var \Next\Session\Environment $environment
     */
    protected $environment;

    /**
     * Enforcing Singleton. Disallow cloning
     */
    private function __clone() {}

    /**
     * Session Constructor
     */
    private function __construct() {
        $this -> handlers = new Handlers( [ 'manager' => $this ] );
    }

    /**
     * Session Object Starter
     *
     * @param string|optional $name
     *  Optional Session Name
     *
     * @param string|optional $id
     *  Optional Session ID.
     *  Mostly used by custom Session Handlers
     *
     * @return Session
     *  Session Instance
     *
     * @throws \Next\Session\SessionException
     *  Session has been already initialized, through a manually
     *  called session_start()
     */
    public static function start( $name = NULL, $id = NULL ) {

        if( NULL === self::$instance ) {

            $currentID = session_id();

            if( ! empty( $currentID ) ) {

                throw new RuntimeException(
                    'Session has been manually started with session_start()'
                );
            }

            self::$instance = new Manager;

            $handler = self::$instance -> handlers -> getHandler();

            // Setting Save Handler, if different than default

            if( ! is_null( $handler ) ) {

                session_set_save_handler(

                    [ $handler, 'open' ],

                    [ $handler, 'close' ],

                    [ $handler, 'read' ],

                    [ $handler, 'write' ],

                    [ $handler, 'destroy' ],

                    [ $handler, 'renew' ]
                );
            }

            // Effectively Initializes the Session

            self::$instance -> init( $name, $id );
        }

        return self::$instance;
    }

    /**
     * Session Starter
     *
     * @param string|optional $name
     *  Optional Session Name
     *
     * @param string|optional $id
     *  Optional Session ID.
     *  Mostly used by custom Session Handlers
     *
     * @throws \Next\Session\SessionException
     *  session_start() failed, returning FALSE
     */
    public function init( $name = NULL, $id = NULL ) {

        // Setting Up Session Name

        if( ! empty( $name ) ) {
            self::setSessionName( $name );
        }

        // Setting up Session ID

        if( ! empty( $id ) ) {
            self::setSessionID( $id );
        }

        // Initializing the Session

        $test = session_start();

        if( ! $test ) {
            throw new RuntimeException( 'Session initialization failure' );
        }

        // Initializing the Default Session Environment

        $this -> environment = new Environment(
            [ 'name' => session_name() ]
        );
    }

    /**
     * Destroy the Session and the Session Object
     */
    public function destroy() {

        // This should be done manually, through Environment::unsetAll(), but in any case...

        $_SESSION = [];

        // Removing Session Cookie

        if( isset( $_COOKIE[ session_name() ] ) ) {

            $params = session_get_cookie_params();

            setcookie(

                session_name(),

                FALSE,

                315554400, // strtotime('1980-01-01'),

                $params['path'], $params['domain'], $params['secure']
            );
        }

        // Resetting Session Properties

        self::$instance = NULL;

        $this -> savePath = NULL;

        // Removes Session Data (again >.<)

        session_unset();
    }

    /**
     * Regenerate Session ID
     *
     * @return Session
     *  Session Object (Fluent Interface)
     */
    public function regenerateID() {

        session_regenerate_id();

        return $this;
    }

    /**
     * Commit current Session Data
     */
    public function commit() {
        session_write_close();
    }

    // Accessors

    /**
     * Get current Session Data
     *
     * @return string
     *  Encoded Session Data
     */
    public function getSessionData() {
        return session_encode();
    }

    /**
     * Get Handlers Management Object
     *
     * @return \Next\Session\Handlers
     *  Session Handler Management Object
     */
    public function getHandlersManager() {
        return $this -> handlers;
    }

    /**
     * Get default Session Environment
     *
     * @return \Next\Session\Environment
     *  Default Session Environment
     */
    public function getEnvironment() {
        return $this -> environment;
    }

    // Getters / Setters

    /**
     * Get Session Name
     *
     * @return string
     *  Session Name
     */
    public function getSessionName() {
        return session_name();
    }

    /**
     * Set Session Name
     *
     * @param string $sessionName
     *  Session Name
     */
    public static function setSessionName( $sessionName ) {
        session_name( $sessionName );
    }

    /**
     * Get Session ID
     *
     * @return string
     *  Session ID
     */
    public function getSessionID() {
        return session_id();
    }

    /**
     * Set Session ID
     *
     * @param string $id
     *  Session ID
     */
    public static function setSessionID( $id ) {
        session_id( $id );
    }

    /**
     * Get Session Cache Lifetime
     *
     * @return integer
     *  Session Max Lifetime
     */
    public function getSessionLifetime() {
        return self::$lifetime;
    }

    /**
     * Set Session Cache Lifetime
     *
     * @param integer $lifetime
     *  Session Lifetime
     */
    public function setSessionLifetime( $lifetime ) {

        self::$lifetime =& $lifetime;

        session_cache_expire( $lifetime );
    }

    /**
     * Get Session Save Path
     *
     * @return string
     *  Session SavePath
     */
    public function getSessionSavePath() {
        return $this -> savePath;
    }

    /**
     * Set Session Save Path
     *
     * @param string $savePath
     *  Session Save Path
     *
     * @return Session
     *  Session Object (Fluent Interface)
     */
    public function setSessionSavePath( $savePath ) {

        $this -> savePath =& $savePath;

        session_save_path( $savePath );

        return $this;
    }
}