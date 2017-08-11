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

use Next\Session\SessionException;    # Session Exception Class

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
     * Session ID
     *
     * @staticvar string $id
     */
    private static $ID = 'PHPSESSID';

    /**
     * Session Name
     *
     * @staticvar string $name
     */
    private static $name = 'Session';

    /**
     * Session Save Path
     *
     * @var string $savePath
     */
    private $savePath;

    /**
     * Session Max Lifetime
     *
     * @staticvar integer $lifetime
     */
    private static $lifetime = 180;

    /**
     * Session Handlers Manager
     *
     * @var \Next\Session\Handlers $handlers
     */
    private $handlers;

    /**
     * Enforcing Singleton. Disallow cloning
     */
    private function __clone() {}

    /**
     * Session Constructor
     */
    private function __construct() {
        $this -> handlers = new Handlers( $this );
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

                throw SessionException::alreadyInitiated();
            }

            self::$instance = new Manager;

            $handler = self::$instance -> handlers -> getHandler();

            // Setting Save Handler, if different than default

            if( ! is_null( $handler ) ) {

                session_set_save_handler(

                    array( $handler, 'open' ),

                    array( $handler, 'close' ),

                    array( $handler, 'read' ),

                    array( $handler, 'write' ),

                    array( $handler, 'destroy' ),

                    array( $handler, 'renew' )
                );
            }

            // Effectivelly Initializes the Session

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

        self::setSessionName( empty( $name ) ? self::$name : $name );

        // Setting up Session ID

        self::setSessionID( empty( $id ) ? self::$ID : $id );

        // Initializing the Session

        $test = session_start();

        if( ! $test ) {

            throw SessionException::initializationFailure();
        }
    }

    /**
     * Destroy the Session and the Session Object
     */
    public function destroy() {

        // This should be done manually, through Environment::unsetAll(), but in any case...

        $_SESSION = array();

        // Removing Session Cookie

        if( isset( $_COOKIE[ self::$name ] ) ) {

            $params = session_get_cookie_params();

            setcookie(

                self::$name,

                FALSE,

                315554400, // strtotime('1980-01-01'),

                $params['path'], $params['domain'], $params['secure']
            );
        }

        // Resetting Session Properties

        self::$instance = NULL;

        self::$name = NULL;

        self::$ID = NULL;

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

        self::$ID = session_id();

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

    // Getters / Setters

    /**
     * Get Session Name
     *
     * @return string
     *  Session Name
     */
    public function getSessionName() {
        return self::$name;
    }

    /**
     * Set Session Name
     *
     * @param string $sessionName
     *  Session Name
     */
    public static function setSessionName( $sessionName ) {

        self::$name =& $sessionName;

        session_name( $sessionName );
    }

    /**
     * Get Session ID
     *
     * @return string
     *  Session ID
     */
    public function getSessionID() {
        return self::$ID;
    }

    /**
     * Set Session ID
     *
     * @param string $id
     *  Session ID
     */
    public static function setSessionID( $id ) {

        self::$ID =& $id;

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