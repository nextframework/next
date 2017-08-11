<?php

/**
 * Sessions Handler Class | Session\Handlers.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Session;

use Next\Components\Object;                     # Object Class

use Next\Session\Handlers\HandlersException;    # Session Handlers Exception Class
use Next\Session\Handlers\Handler;              # Session Handlers Interface
use Next\Components\Collections\Set;            # Set Iterator Class

/**
 * Session Handlers Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Handlers extends Object {

    /**
     * Handlers Set
     *
     * @var \Next\Components\Collections\Set $handlers
     */
    private $handlers;

    /**
     * Active Handler
     *
     * @var \Next\Session\Handlers\Handler $handler
     */
    private $handler;

    /**
     * Session Object
     *
     * @var Session $session
     */
    private $session;

    /**
     * Session Handlers Constructor
     *
     * @param \Next\Session\Manager $session
     *  Session Object
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the Session Handler
     */
    public function __construct( Manager $session, $options = NULL ) {

        parent::__construct( $options );

        $this -> handlers = new Set;

        $this -> session =& $session;
    }

    /**
     * Add a new Session Handler
     *
     * @param \Next\Session\Handlers\Handler $handler
     *  Session Handler
     *
     * @param boolean|optional $activate
     *  Flag to condition the immediate activation of Handler
     *
     * @return \Next\Session\Handlers
     *  Session Handlers Object (Fluent Interface)
     */
    public function addHandler( Handler $handler, $activate = FALSE ) {

        // Adding Session Handler

        $this -> handlers -> add( $handler );

        // Should we activate this handler right now?

        if( $activate !== FALSE ) {

            $this -> changeHandler( $handler );
        }

        return $this;
    }

    /**
     * Change current Session Handler
     *
     * @param string|\Next\Session\Handlers\Handler $handler
     *  Handler Object or Handler Name
     *
     * @throws \Next\Session\Handlers\HandlerException
     *  Changing Session Handler to a invalid Handler, characterized as
     *  instance of \Next\Session\Hndlers\Handler
     */
    public function changeHandler( $handler ) {

        // If we don't have a true Handler...

        if( ! $handler instanceof Handler ) {

            // ... let's find an assigned Handler that matches given string

            $test = $this -> handlers -> find( $handler );

            // Nothing Found?

            if( ! $test instanceof Handler ) {

                throw HandlersException::unknownHandler( (string) $handler );
            }

            // Yeah! We're ninjas!

            $handler = $test;
        }

        // Committing Session

        $this -> session -> commit();

        // Setting Session Options with Handler Options

        if( isset( $handler -> getOptions -> savePath ) ) {

            $this -> session -> setSessionSavePath(

                $handler -> getOptions -> savePath
            );
        }

        $lifetime = ( isset( $handler -> getOptions -> lifetime ) ?
                        $handler -> getOptions -> lifetime : 0 );

        if( $lifetime > 0 ) {

            $this -> session -> setSessionLifetime( $lifetime );
        }

        // Changing current Session Handler

        $this -> handler =& $handler;

        // Restarting Session

        $this -> session -> init( $this -> session -> getSessionName(), $this -> session -> getSessionID() );
    }

    // Accessors

    /**
     * Get active Session Handler
     *
     * @return \Next\Session\Handlers\Handler
     */
    public function getHandler() {
        return $this -> handler;
    }
}
