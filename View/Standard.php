<?php

/**
 * Standard View Engine | View\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\View;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Components\Object;                   # Object Class
use Next\FileSystem\Path;                     # FileSystem Path Data-type Class
use Next\View\ViewException;                  # View Exception Class

/**
 * Standard View Engine Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends Object implements Verifiable, View {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'application'     => [ 'type' => 'Next\Application\Application', 'required' => TRUE ],

        /**
         * Template Variables Behaviour
         *
         * This will be used in \Next\View\Standard::__get() to define
         * if a template variable will be echoed or just returned —
         * for variable assignment, manual echo...
         *
         * If TRUE, it will be returned. Otherwise, it will be echoed.
         * Defaults to TRUE
         *
         * Note: Array and Objects will ALWAYS be returned, regardless
         * the value set in this Parameter Option
         */
        'returnVariables' => [ 'required' => FALSE, 'default' => TRUE ],

        /**
         * Template Files Basepath.
         * Defines a start point from which the Templates will be
         * searched for inclusion
         */
        'basepath'        => [ 'required' => FALSE ],

        /**
         * Template Files Subpath
         *
         * A Subpath is a substring common to all Template File Paths,
         * present in every call to \next\View\View::render()
         * within a next\Controller\Controller
         *
         * If defined and not empty — otherwise it could end up resulting
         * in malformed filepaths — the subpath will be inserted between
         * the BasePath and the current path values during
         * Template File Search iteration
         *
         * Note: Even using :subpath in your Template FileSpec, sometimes,
         *       you have to use a manual SubPath too, specially when
         *       your Template Files are in a different directory
         *       levels than your Controllers
         */
        'subpath'         => [ 'required' => FALSE ],

        /**
         * This Parameter Option configures the View Engine to whether
         * or not the Template Filespec will be used during
         * File Search auto-detection
         * Defaults to TRUE, after all, if disabled every single call
         * to View::render() would have to provide a File to be rendered,
         * which defeats the purpose of the auto-rendering provided
         * by \Next\Controller\AbstractController::_destruct()
         */
        'useFileSpec'     => [ 'required' => FALSE, 'default' => TRUE ],

        /**
         * This Parameter Option configures the View Engine with a more
         * or less predictable directory structure in which the
         * Template Files can be located.
         * By default considers:
         * - The Template Subpath (:subpath), if provided as a
         *   non-empty string (see above)
         * - A clean version — i.e. without the known keyword 'Controller' —
         *   of the \Next\Controller\Controller (:controller) and the
         *   Action Method (:action) in which the call to View::render()
         *   occurred.
         */
        'fileSpec'        => [ 'required' => FALSE, 'default' => ':subpath/:controller/:action' ],

        /**
         * Default Template File Extension.
         * Defaults to 'phtml' as a geek remembrance of the old PHP 2 file formats >.<
         */
        'extension'       => [ 'required' => FALSE, 'default' => 'phtml' ],

        /**
         * Default Template File
         * For cases of such small applications that only one
         * Template File is enough, setting this — usually through
         * AbstractController::init() — allows View::render() to be
         * called without arguments or even letting
         * AbstractController::__destruct() to do everything for you ;)
         */
        'defaultTemplate' => [ 'required' => FALSE, 'default' => NULL ]
    ];

    /**
     * View Helpers
     *
     * @var array $_helpers
     */
    private $_helpers = [

        'route'   => 'Next\View\Helper\Route',
        'session' => 'Next\View\Helper\Session'
    ];

    /**
     * Template Variables
     *
     * @var array $_tplVars
     */
    private $_tplVars = [];

    /**
     * Forbidden Template Variables Names
     *
     * @var array $_forbiddenTplVars
     */
    private $_forbiddenTplVars = [

        // Internal Class Properties

        '_helpers',
        '_tplVars',
        '_forbiddenTplVars',
        '_paths',
        '_shouldRender',

        /**
         * @internal
         *
         * These are internal resources coming from
         * \Next\Application\Application or one of
         * \Next\Controller\Controller when used through
         * \Next\Controller\AbstractController::__get() or
         * \Next\Controller\AbstractController::__set()
         */
        'view',
        'request',
        'session'
    ];

    /**
     * Template Views Paths
     *
     * @var array $_paths
     */
    private $_paths = [];

    /**
     * Flag to define whether or not Template File should be rendered
     *
     * @var boolean $_shouldRender
     */
    private $_shouldRender = TRUE;

    /**
     * Additional Initialization.
     * Configures resources based on \Next\Application\Application
     * provided and apply quik fixes over optional Parameter Options
     */
    protected function init() {

        // Resetting Default Assignments

        $this -> resetDefaults();

        /**
         * @internal
         *
         * Because Template Files Subpath is inserted between the
         * Basepath and the current value on iteration, surrounded by
         * slashes, if a Template Files Subpath is set as an empty
         * string this can result in paths with double slashes
         * which *may* cause problems
         */
        if( ! is_string( $this -> options -> subpath ) ||
                empty( $this -> options -> subpath ) ) {

            $this -> options -> subpath = NULL;
        }
    }

    /**
     * Reset View to its defaults
     *
     * User defined Template Variables are removed leaving only the
     * pre-assigned variables.
     *
     * If, for some reason, it's already empty, simply create them.
     *
     * @return \Next\View\View
     *  View Object (Fluent Interface)
     */
    public function resetDefaults() {

        $this -> _tplVars[ 'request' ]  = $this -> options -> application -> getRequest();

        if( ( $session = $this -> options -> application -> getSession() ) !== NULL &&
                session_status() == PHP_SESSION_ACTIVE ) {

            $this -> _tplVars[ 'session' ]  = $session -> getEnvironment();
        }

        // Unregistering any possible Exception Message

        unset( $this -> _tplVars[ '__EXCEPTION__' ] );

        return $this;
    }

    // View Helper-related Method

    /**
     * Register a new Template View Helper
     *
     * @param \Next\View\Helper\Helper|string $helper
     *  Template View Helper
     *
     * @return \Next\View\View
     *  View Object (Fluent Interface)
     */
    public function registerHelper( $helper ) {

        $this -> _helpers[ (string) $helper ] = $helper;

        return $this;
    }

    // Views File-related Methods

    /**
     * Get default File Extension
     *
     * @return string
     *  Template Views File Extensions
     */
    public function getExtension() {
        return $this -> options -> extension;
    }

    // Views Path-related Methods

    /**
     * Get current Basepath
     *
     * @return string
     *  Template Views Files Basepath
     */
    public function getBasepath() {
        return $this -> options -> basepath;
    }

    /**
     * Get current Subpath
     *
     * @return string
     *  Templates Views Files Subpath
     */
    public function getSubpath() {
        return $this -> options -> subpath;
    }

    /**
     * Add more paths to Template Files Location
     *
     * @param string $path
     *  Another Path to locate Template Views
     *
     * @return \Next\View\View
     *  View Object (Fluent Interface)
     */
    public function addPath( $path ) {

        // Cleaning extra boundaries slashes and reverting its slashes

        $path = new String( [ 'value' => $path ] );

        if( ! in_array( $path, $this -> _paths ) ) {

            $this -> _paths[] = $path -> clean() -> get();
        }

        return $this;
    }

    /**
     * Get current paths
     *
     * @return array
     *  List of paths to search Template Views
     */
    public function getPaths() {
        return $this -> _paths;
    }

    /**
     * Get FileSpec definition
     *
     * @return string
     *  FileSpec Definition
     */
    public function getFileSpec() {
        return $this -> options -> fileSpec;
    }

    // Template Variables-related Methods

    /**
     * Get Variable Behavior
     *
     * @return boolean
     *  Template Variables behavior
     */
    public function getVariablesBehavior() {
        return $this -> options -> returnVariables;
    }

    /**
     * Assign one or more Template Variables
     *
     * @param string|array $tplVar
     *  Template Variable Name or an array of Variables
     *
     * @param mixed|optional $value
     *  Value for Template Variable when <strong>$tplVar</strong>
     *  is not an array
     *
     * @return \Next\View\View
     *  View Object (Fluent Interface)
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Chosen Template Variable is defined as reserved
     */
    public function assign( $tplVar, $value = NULL ) {

        // Working recursively...

        if( (array) $tplVar === $tplVar ) {

            foreach( $tplVar as $_tplVar => $_value ) {

                $this -> assign( $_tplVar, $_value );
            }

        } else {

            if( in_array( $tplVar, $this -> _forbiddenTplVars ) ||
                    array_key_exists( $tplVar, $this -> _helpers ) ) {

                throw ViewException::forbiddenVariable( $tplVar );
            }

            // Creating a new Template Variable

            $this -> _tplVars[ $tplVar ] = $value;
        }

        return $this;
    }

    /**
     * Get all assigned Template Variables
     *
     * @return array
     *  Template Variables
     */
    public function getVars() {
        return $this -> _tplVars;
    }

    /**
     * Get an specific Template Variable assigned
     *
     * @param string $tplVar
     *  The Template Variable
     *
     * @return mixed
     *  The Template Variable assigned if found. NULL otherwise
     */
    public function getVar( $tplVar ) {

        return ( array_key_exists( $tplVar, $this -> _tplVars ) ?
                    $this -> _tplVars[ $tplVar ] : NULL );
    }

    // Page renderer

    /**
     * Get Default Template
     *
     * @return string
     *  Default Template View File
     */
    public function getDefaultTemplate() {
        return $this -> options -> defaultTemplate;
    }

    /**
     * Render the page, outputs the buffer
     *
     * @param string|optional $name
     *  Template View File to render. If NULL, we'll try to find it
     *
     * @param boolean|optional $search
     *  Flag to condition whether or not we should try to find the
     *  proper Template View File automatically
     *
     * @return \Next\HTTP\Response
     *  Response Object
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if unable to find Template View File
     */
    public function render( $name = NULL, $search = TRUE ) {

        $response = $this -> options -> application -> getResponse();

        /**
         * @internal
         *
         * The Template Rendering will not happen if:
         *
         * - It shouldn't, meaning \Next\View\View::disableRender()
         *   has been called
         * - Any kind of output has already been sent,
         *   like a debugging purposes var_dump()
         */
        if( ! $this -> _shouldRender || ob_get_length() != 0 ) {
            return $response;
        }

        if( $search === FALSE && empty( $name ) ) {
            throw ViewException::unableToFindFile();
        }

        // Including Template File

        ob_start();

            // ... manually

        if( $search === FALSE ) {

            if( ( $file = stream_resolve_include_path( $name ) ) === FALSE ) {
                throw ViewException::missingFile( $name );
            }

            include $file;

        } else {

            // ... or automatically

            include $this -> findFile( $name );
        }

        // Adding Main View Content to Response Body

        $response -> appendBody( ob_get_clean() );

        /**
         * Something was rendered, so let's disallow another
         * rendering for this Request
         */
        $this -> _shouldRender = FALSE;

        return $response;
    }

    // Accessory Methods

    /**
     * Disable Rendering process
     *
     * @return \Next\View\View
     *  View Object (Fluent Interface)
     */
    public function disableRender() {

        $this -> _shouldRender = FALSE;

        return $this;
    }

    /**
     * (Re-)Enables Rendering process
     *
     * @return \Next\View\View
     *  View Object (Fluent Interface)
     */
    public function enableRender() {

        $this -> _shouldRender = TRUE;

        return $this;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if provided Template View FileSpec is not minimally
     *  valid (i.e at least one string led by a colon)
     */
    public function verify() {

        if( preg_match( '/(:\w+\/?)+/', $this -> options -> fileSpec ) == 0 ) {

            throw new InvalidArgumentException(

                sprintf(

                    'Invalid Template View FileSpec for
                    Application <strong>%s</strong>',

                    $this -> options -> application -> getClass() -> getName()
                )
            );
        }
    }

    // OverLoading

    /**
     * Checks if a Template Variable Exists.
     * Note that the PRESENCE of key is tested, not its value
     *
     * @param string $tplVar
     *  Template Variable to be tested
     *
     * @return boolean
     *  TRUE if desired Template Variable exists and FALSE otherwise
     */
    public function __isset( $tplVar ) {
        return array_key_exists( $tplVar, $this -> _tplVars );
    }

    /**
     * Unsets a Template Variable
     * Note that the PRESENCE of key is tested before unset it, not its value
     *
     * @param string $tplVar
     *  Template Variable to be deleted
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Trying to unset a undefined Template Variable
     *
     * @throws \Next\Exception\Exceptions\AccessViolationException
     *  Trying to unset a Template Variable whose name has been marked
     *  as forbidden or forbidden due an association with a View Engine Helper
     */
    public function __unset( $tplVar ) {

        if( in_array( $tplVar, $this -> _forbiddenTplVars ) ) {
            throw ViewException::forbiddenVariable( $tplVar );
        }

        if( ! array_key_exists( $tplVar, $this -> _tplVars ) ) {
            throw ViewException::missingVariable( $tplVar );
        }

        unset( $this -> _tplVars[ $tplVar ] );
    }

    /**
     * Create a new Template Value and assigns its value
     *
     * @param string $tplVar
     *  Template Variable Name
     *
     * @param mixed|optional $value
     *  Template Variable Value
     *
     * @throws \Next\Exception\Exceptions\AccessViolationException
     *  Trying to set a Template Variable with a name that has been marked
     *  as forbidden or forbidden due an association with a View Engine Helper
     */
    public function __set( $tplVar, $value = NULL ) {

        if( in_array( $tplVar, $this -> _forbiddenTplVars ) ) {
            throw ViewException::forbiddenVariable( $tplVar );
        }

        $this -> _tplVars[ $tplVar ] = $value;
    }

    /**
     * Return/Echo the current value of a Template Variable
     * Note that the PRESENCE of key is tested before return/echo it,
     * not its value
     *
     * @param string $tplVar
     *
     *  Template Variable to be displayed or retrieved, accordingly to
     *   the Parameter Option **$returnVariables**
     *
     * @return mixed|void
     *  If Parameter Option **returnVariables** was set to TRUE -OR-
     *  **$tplVar** represents an array or an Object, the desired
     *  Template Variable will be returned.
     *  Otherwise, variable will be echoed and thus, nothing will be returned
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Trying to retrieve/output data from an undefined Template Variable
     */
    public function __get( $tplVar ) {

        if( ! array_key_exists( $tplVar, $this -> _tplVars ) ) {
             throw ViewException::missingVariable( $tplVar );
        }

        // Should/Must we return?

        if( $this -> options -> returnVariables !== FALSE ||
              ( (array) $this -> _tplVars[ $tplVar ] === $this -> _tplVars[ $tplVar ]  ||
                  is_object( $this -> _tplVars[ $tplVar ] ) ) ) {

            return $this -> _tplVars[ $tplVar ];
        }

        // Or echo it?

        echo $this -> _tplVars[ $tplVar ];
    }

    /**
     * Allows View Helpers to be called in this Object context
     *
     * @param string $helper
     *  The View Helper
     *
     * @param array|optional $args
     *  Variable list of arguments to the helper
     *
     * @return mixed|boolean
     *  Return what the helper returns or FALSE if a ReflectionException
     *  is caught in \Next\Components\Context::call()
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if View Helper cannot be recognized among registered ones
     */
    public function __call( $helper, array $args = [] ) {

        if( ! array_key_exists( $helper, $this -> _helpers ) ) {
            throw ViewException::unknownHelper( $helper );
        }

        $helper = $this -> _helpers[ $helper ];

        return call_user_func_array(
            ( is_string( $helper ) ? new $this -> _helpers[ $helper ] : $helper ), $args
        );
    }

    // Auxiliary Methods

    /**
     * Find given Template View file in defined Views Directories
     *
     * @param string|optional $file
     *  Template View File
     *
     * @return string
     *  Template View Filepath
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if template Filepath wasn't provide and the auto-searching
     *  feature through Template View FileSpec is turned off
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if there are no paths assigned to iterate and search for the file
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if a Template File could be found
     */
    private function findFile( $file = NULL ) {

        // If we don't have a file, let's use the Default Template, if any

        $file = ( ! empty( $file ) ? $file : $this -> options -> defaultTemplate );

        // Cleaning dots and slashes around Template View Filename

        $file = trim( $file, './' );

        // If still empty, let's check if FileSpec detection is enabled

        if( empty( $file ) && ! $this -> options -> useFileSpec ) {
            throw ViewException::disabledFileSpec();
        }

        // No paths to iterate?

        if( count( $this -> _paths ) == 0 && $this -> options -> basepath === NULL )  {
            throw ViewException::noPaths( $file );
        }

        // If we don't have a Template View Name, we will use the FileSpec ability

        $file = ( ! empty( $file ) ? $file : $this -> findFileBySpec() );

        // Adding File extension, if no one was defined yet

        if( strrpos( $file, $added = sprintf( '.%s', $this -> options -> extension ) ) === FALSE ) {
            $file .= $added;
        }

        // Do we have more than one View Path?

        /**
         * @internal
         * Test if this separation is REALLY necessary
         */
        if( count( $this -> _paths ) == 0 ) {

            // Building and cleaning the full filepath

            $file = new Path(
                [
                    'value' => sprintf(
                        '%s/%s%s', $this -> options -> basepath, $this -> options -> subpath, $file
                    )
                ]
            );

            $file = $file -> clean() -> get();

            if( is_readable( $file ) ) return $file;

        } else {

            foreach( $this -> _paths as $path ) {

                // Building and cleaning the full filepath

                $file = new Path(
                    [
                        'value' => sprintf(
                            '%s/%s/%s%s', $this -> options -> basepath, $path, $this -> options -> subpath, $file
                        )
                    ]
                );

                $file = $file -> clean() -> get();

                if( is_readable( $file ) ) return $file;
            }
        }

        // No file could be found, let's condition Exceptions' Messages

        if( $this -> options -> useFileSpec ) {

            if( ! empty( $this -> options -> subpath ) ) {
                throw ViewException::wrongUseOfSubpath( $file );
            }

            throw ViewException::unableToFindUnderFileSpec( $file );

        }

        throw ViewException::missingFile( $file );
    }

    /**
     * Find the Template View File from FileSpec
     *
     * @return string
     *  Template View FilePath from defined FileSpec
     *
     * @see
     *  \Next\Controller\Router\Router::getController()
     *  \Next\Controller\Router\Router::getMethod()
     */
    private function findFileBySpec() {

        // Known Replacements

        $application    = $this -> options -> application -> getApplicationDirectory();

        $router         = $this -> options -> application -> getRouter();

        $controller     = $router -> getController();
        $action         = $router -> getMethod();

        /**
         * @internal
         * Finding Default SubPath
         *
         * Default SubPath is built by removing from Controllers Name:
         *
         * - Application Directory,
         * - 'Controller' Keyword
         * - Controller ClassName
         */
        $controllerBasename = implode( '', array_slice( explode( '\\', $controller ) , -1 ) );

        $subpath = strtr(

            $controller,

            [ $application => '', self::CONTROLLERS_KEYWORD => '', $controllerBasename => '' ]
       );

        // Windows, Windows, Windows... <_<

        $subpath = trim( strtr( $subpath, [ '\\\\' => '\\' ] ), '\\' );

        // Cleaning Controller Class to find its "Real Name"

        $controller = strtr( $controllerBasename, [ 'Controller' => '' ] );

        // Cleaning known Action suffixes

        $action = strtr(

            $action,

            [ self::ACTION_METHOD_SUFFIX_VIEW => '', self::ACTION_METHOD_SUFFIX_ACTION => '' ]
        );

        // Replacing known matches

        $spec = trim(

            strtr(

                $this -> options -> fileSpec,

                [
                    self::APPLICATION => $application,
                    self::CONTROLLER  => $controller,
                    self::ACTION      => $action,
                    self::SUBPATH     => $subpath
                ]

            ), '/'
        );

        return sprintf(
            '%s.%s', strtolower( $spec ), $this -> options -> extension
        );
    }
}