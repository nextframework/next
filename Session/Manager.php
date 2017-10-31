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
 * The Session Manager handles everything Session-related, from its
 * initialization to its destruction, including custom Session Handlers
 *
 * @package    Next\Session
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Session\Environment
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
     * @return Manager
     *  Session Manager Instance
     *
     * @throws \Next\Session\SessionException
     *  Session has been already initialized, through a manually
     *  called session_start()
     */
    public static function start( $name = NULL, $id = NULL ) : Manager {

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

            if( $handler !== NULL ) {

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
    public function init( $name = NULL, $id = NULL ) : void {

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
    public function destroy() : void {

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
    public function regenerateID() : Manager {

        session_regenerate_id();

        return $this;
    }

    /**
     * Commit current Session Data
     */
    public function commit() : void {
        session_write_close();
    }

    // Accessors

    /**
     * Get current Session Data
     *
     * @return string
     *  Encoded Session Data
     */
    public function getSessionData() : string {
        return session_encode();
    }

    /**
     * Get Handlers Management Object
     *
     * @return \Next\Session\Handlers
     *  Session Handler Management Object
     */
    public function getHandlersManager() : Handlers {
        return $this -> handlers;
    }

    /**
     * Get default Session Environment
     *
     * @return \Next\Session\Environment
     *  Default Session Environment
     */
    public function getEnvironment() : Environment {
        return $this -> environment;
    }

    // Getters / Setters

    /**
     * Get Session Name
     *
     * @return string
     *  Session Name
     */
    public function getSessionName() : string {
        return session_name();
    }

    /**
     * Set Session Name
     *
     * @param string $sessionName
     *  Session Name
     */
    public static function setSessionName( $sessionName ) : void {
        session_name( $sessionName );
    }

    /**
     * Get Session ID
     *
     * @return string
     *  Session ID
     */
    public function getSessionID() : string {
        return session_id();
    }

    /**
     * Set Session ID
     *
     * @param string $id
     *  Session ID
     */
    public static function setSessionID( $id ) : void {
        session_id( $id );
    }

    /**
     * Get Session Cache Lifetime
     *
     * @return integer
     *  Session Max Lifetime
     */
    public function getSessionLifetime() : int {
        return self::$lifetime;
    }

    /**
     * Set Session Cache Lifetime
     *
     * @param integer $lifetime
     *  Session Lifetime
     */
    public function setSessionLifetime( $lifetime ) : void {

        self::$lifetime = $lifetime;

        session_cache_expire( $lifetime );
    }

    /**
     * Get Session Save Path
     *
     * @return string
     *  Session SavePath
     */
    public function getSessionSavePath() : string {
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
    public function setSessionSavePath( $savePath ) : Manager {

        $this -> savePath = $savePath;

        session_save_path( $savePath );

        return $this;
    }
}