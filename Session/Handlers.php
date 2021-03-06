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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;             # Object Class

use Next\Session\Handlers\Handler;      # Session Handlers Interface
use Next\Components\Collections\Set;    # Set Iterator Class

/**
 * The Session Handlers Manager controls different Session Handlers, by adding
 * them to a Set Collection but more important it deals with the Handler switching
 *
 * @package    Next\Session
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object
 *             Next\Session\Handlers\Handler
 *             Next\Components\Collections\Set
 */
class Handlers extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'manager' => [ 'type' => 'Next\Session\Manager', 'required' => TRUE ]
    ];

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
     * Session Manager Object
     *
     * @var \Next\Session\Manager $manager
     */
    private $manager;

    /**
     * Additional Initialization.
     * Creates a new Collection set for all Session Handlers to be
     * registered and a shortcut for Next\Session\Manager
     */
    protected function init() : void {

        $this -> handlers = new Set;

        $this -> manager  = $this -> options -> manager;
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
    public function addHandler( Handler $handler, $activate = FALSE ) : Handlers {

        // Adding Session Handler

        $this -> handlers -> add( $handler );

        // Should we activate this handler right now?

        if( $activate !== FALSE ) {
            $this -> changeHandler( $handler );
        }

        return $this;
    }

    /**
     * Switch to a different Session Handler
     *
     * @param string|\Next\Session\Handlers\Handler $handler
     *  Handler Object or Handler Name
     *
     * @throws \Next\Session\Handlers\HandlerException
     *  Changing Session Handler to a invalid Handler, characterized as
     *  instance of Next\Session\Handlers\Handler
     */
    public function switch( $handler ) : void {

        // If we don't have a true Handler...

        if( ! $handler instanceof Handler ) {

            // ... let's find an assigned Handler that matches given string

            $test = $this -> handlers -> find( $handler );

            // Nothing Found?

            if( $test === -1 || $test instanceof Handler ) {

                throw new InvalidArgumentException(

                    sprintf(

                        'Handler <strong>%s</strong> not found within
                        Session Handlers Collection',

                        $handler
                    )
                );
            }

            // Yeah! We're ninjas!

            $handler = $test;
        }

        // Committing Session

        $this -> manager -> commit();

        // Setting Session Options with Handler Options

        if( isset( $handler -> getOptions -> savePath ) ) {

            $this -> manager -> setSessionSavePath(
                $handler -> getOptions -> savePath
            );
        }

        $lifetime = ( isset( $handler -> getOptions -> lifetime ) ?
                        $handler -> getOptions -> lifetime : 0 );

        if( $lifetime > 0 ) {
            $this -> manager -> setSessionLifetime( $lifetime );
        }

        // Changing current Session Handler

        $this -> handler = $handler;

        // Restarting Session

        $this -> manager -> init(
            $this -> manager -> getSessionName(),
            $this -> manager -> getSessionID()
        );
    }

    // Accessors

    /**
     * Get active Session Handler
     *
     * @return \Next\Session\Handlers\Handler|void
     *  Active Handler, after Handlers::change() has been called
     */
    public function getHandler() :? Handler {
        return $this -> handler;
    }
}
