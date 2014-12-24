<?php

namespace Next\View\Helper;

use Next\Components\Object;                           # Object Class
use Next\Components\Invoker;                          # Invoker Class

use Next\Session\Manager as SessionManager;           # Session Manager Class
use Next\Session\Environment;                         # Session Environment Class
use Next\Session\Environment\EnvironmentException;    # Session Environment Exception Class

use Next\View\ViewException;                          # View Exception Class

class Session extends Object implements Helper {

    /**
     * Session Environment Object
     *
     * @var Next\Session\Environment $environment
     */
    private $environment;

    /**
     * Additional initialization
     * Starts the Session if needed, setup the Session Environment and extends
     * Session Helper context to some methods of Session Environment
     */
    protected function init() {

        SessionManager::start();

        $this -> environment = new Environment( 'session_helper' );

        $this -> extend( new Invoker( $this, $this -> environment, array( 'getEnvironment', 'getAll' ) ) );
    }

    /**
     * Get an entry from $_SESSION through Session Environment
     *
     * @param  string $name
     *  The entry to be retrieved
     *
     * @param  mixed|optional $default
     *  Something to return if desired entry doesn't exist
     *
     * @return mixed
     *  The value in SESSION associated to given entry, if present.
     *  Otherwise, the value defined in <strong>$default</strong>, if defined,
     *  will be returned instead
     *
     * @throws Next\View\ViewException
     *  Thrown if a Next\Session\Environment\EnvironmentException is caught and
     *  the Exception Code is not the one associated to an undefined index, case
     *  in which what is defined in <strong>$default</strong>, if defined, will
     *  be returned instead
     */
    public function get( $name, $default = NULL ) {

        try {

            return $this -> environment -> $name;

        } catch( EnvironmentException $e ) {

            if( $e -> getCode() == EnvironmentException::UNDEFINED_INDEX ) {
                return $default;
            }

            throw new ViewException( $e -> getMessage(), NULL, NULL, $e -> getResponseCode(), $e -> getCallback() );
        }
    }

    // Helper Interface Method Implementation

    /**
     * Get the Helper name to be registered by View Engine
     *
     * @return string
     */
    public function getHelperName() {
        return 'session';
    }
}