<?php

namespace Next\View;

use Next\View\ViewException;                # View Exception Class
use Next\DB\Table\DataGatewayException;     # Data Gateway Exception Class
use Next\Application\Application;           # Application Interface
use Next\Components\Object;                 # Object Class
use Next\View\CompositeQueue;               # Composite View Queue
use Next\File\Tools;                        # File Tools

/**
 * Standard View Engine Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends Object implements View {

    /**
     * Default Priority
     *
     * View Class uses the concept of Composite Views which is
     * nothing more than a heap.
     *
     * Everything with higher priority will be prepended to Response.
     * Everything with lower priority will be appended to Response
     *
     * This constant defines the priority of the Main View
     *
     * @var integer
     */
    const PRIORITY = 50;

    /**
     * Application using the View
     *
     * @var Next\Application\Application $_application
     */
    private $_application;

    /**
     * Composite Views Queue
     *
     * @var Next\View\CompositeQueue $_queue
     */
    private $_queue;

    /**
     * Partial View Priority
     *
     * @var integer $_priority
     */
    private $_priority = 0;

    /**
     * Template Variables
     *
     * @var array $_tplVars
     */
    private $_tplVars = array();

    /**
     * Forbidden Template Variables Names
     *
     * @var array $_forbiddenTplVars
     */
    private $_forbiddenTplVars = array(

        // Internal Class Properties

        '_application',
        '_queue',
        '_priority',
        '_tplVars',
        '_forbiddenTplVars',
        '_defaultVariableBehavior',
        '_basepath',
        '_subpath',
        '_paths',
        '_useFileSpec',
        '_fileSpec',
        '_extension',
        '_shouldRender',
        '_defaultTemplate',

        // This is the View Engine property in AbstractController

        'view',

        // Internal resources (auto-assigned Variables)

        'request'
    );

    /**
     * Template Variables Behavior
     *
     * This will be used in Magic Method __get() to define if a template variable
     * will be echoed or just returned (for variable assignment, manual echo...)
     *
     * If TRUE, it will be returned. Otherwise, it will be echoed.
     *
     * Note: Array and Objects will ALWAYS be returned, regardless this option
     *
     * @var boolean $_returnVariables
     */
    private $_returnVariables = TRUE;

    /**
     * Basepath for Template Views Files
     *
     * @var string $_basepath
     */
    private $_basepath;

    /**
     * Subpath for Template Views Files
     *
     * @var string $_subpath
     */
    private $_subpath;

    /**
     * Template Views Paths
     *
     * @var array $_paths
     */
    private $_paths = array();

    /**
     * Flag to define whether or not the FileSpec will be used
     * for auto-detection
     *
     * @var boolean $_useFileSpec
     */
    private $_useFileSpec = TRUE;

    /**
     * Template Views File Spec
     *
     * @var string $_fileSpec
     */
    private $_fileSpec = ':subpath/:controller/:action';

    /**
     * Template Views File Extension
     *
     * @var string $_extension
     */
    private $_extension = 'phtml';

    /**
     * Flag to define whether or not Template File should be rendered
     *
     * @var boolean $_shouldRender
     */
    private $_shouldRender = TRUE;

    /**
     * Default Template File
     *
     * In case you want a single template file for all actions of Controller
     *
     * This is usually used in Next\Controller\AbstractController::init() method
     *
     * @var string $_defaultTemplate
     */
    private $_defaultTemplate;

    /**
     * View Constructor
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function __construct( Application $application = NULL ) {

        // Setting Up Composite View Queue

        $this -> _queue = new CompositeQueue;

        /**
         * @internal
         * Setting Up Application features only if we have an Application
         * Object.
         *
         * This is useful to not overload every Partial View with useless
         * information
         */
        if( ! is_null( $application ) ) {

            // Setting Up the Application Object

            $this -> _application =& $application;

            // Resetting Default Assignments

            $this -> resetDefaults();
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
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function resetDefaults() {

        $this -> _tplVars[ 'request' ]  = $this -> _application -> getRequest();

        // Unregistering any possible Exception Message

        unset( $this -> _tplVars[ '__EXCEPTION__' ] );

        return $this;
    }

    // Composite Vuews-related Methods

    /**
     * Add a new Composite View to be rendered
     *
     * @param Next\View\View $view
     *  Composite View to be added
     *
     * @param integer $priority
     *
     *  Priority of the Composite View
     *
     *  Priorities higher than this class priority,
     *  will be prepended to Response.
     *
     *  Priorities lower than this class priority,
     *  will be appended to Response
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     *
     * @throws Next\View\ViewException
     *  Composite View has an invalid priority
     */
    public function addView( View $view, $priority = 0 ) {

        $this -> _queue -> add( $view );

        return $this;
    }

    /**
     * Set View Priority
     *
     * This value is only considered for Partial Views.
     * For the Main View, the constant value is used instead
     *
     * Partial Views Priorities must be greater than zero
     * and must not conflict with the value defined in the mentioned
     * constant
     *
     * @param integer $priority
     *  Partial View Priority
     *
     * @return Next\View\View
     *
     * @throws Next\View\ViewException
     *  Given priority is the same of Main View Priority, defined
     *  in PRIORITY constant
     */
    public function setPriority( $priority ) {

        $priority = (int) $priority;

        if( $priority == self::PRIORITY || $priority == 0 ) {
            throw ViewException::invalidPriority( $this, $priority );
        }

        $this -> _priority = $priority;

        return $this;
    }

    /**
     * Get View Priority
     *
     * @return integer
     *  Partial View Priority
     */
    public function getPriority() {
        return $this -> _priority;
    }

    /**
     * Get Composite Views Queue Object
     *
     * @return Next\View\CompositeQueue
     */
    public function getCompositeQueue() {
        return $this -> _queue;
    }

    // Views File-related Methods

    /**
     * Set a new default File Extension
     *
     * If the file extension is not specified in render() call, it will be added
     * automatically
     *
     * @param string $extension
     *  Template ViewS File Extensions
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function setExtension( $extension ) {

        $this -> _extension = ltrim( $extension, '.' );

        return $this;
    }

    /**
     * Get default File Extension
     *
     * @return string
     *  Template Views File Extensions
     */
    public function getExtension() {
        return $this -> _extension;
    }

    // Views Path-related Methods

    /**
     * Set a basepath to be prepended in Template Files Location
     *
     * @param string $path
     *  Template Views Bsepath
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function setBasepath( $path ) {

        $this -> _basepath = Tools::cleanAndInvertPath( $path );

        return $this;
    }

    /**
     * Get current Basepath
     *
     * @return string
     *  Template Views Files Basepath
     */
    public function getBasepath() {
        return $this -> _basepath;
    }

    /**
     * Subpath for Template Views Files
     *
     * Subpaths are the portion of Template Views File Paths which is common in all
     * render() calls in each Controller.
     *
     * Setting this, makes unnecessary repeat this "prefix" in every Template View Filename
     *
     * The subpath will be inserted between BasePath and current path values
     * during iteration
     *
     * If empty, the subpath will be removed, avoiding a malformed filepath
     *
     * Note: Even using :subpath in FileSpec, sometimes, you have to use a manual SubPath too,
     *       specially when your Template Files are in a different directory levels than your Controllers
     *
     * @param string $path
     *  Template Views Optional Subpath
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function setSubpath( $path ) {

        if( ! empty( $path ) ) {

            $this -> _subpath = sprintf( '%s/', Tools::cleanAndInvertPath( $path ) );

        } else {

            $this -> _subpath = NULL;
        }

        return $this;
    }

    /**
     * Get current Subpath
     *
     * @return string
     *  Templates Views Files Subpath
     */
    public function getSubpath() {
        return $this -> _subpath;
    }

    /**
     * Add more paths to Template Files Location
     *
     * @param string $path
     *  Another Path to locate Template Views
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function addPath( $path ) {

        // Cleaning extra boundaries slashes and reverting its slashes

        $path = Tools::cleanAndInvertPath( $path );

        if( ! in_array( $path, $this -> _paths ) ) {

            $this -> _paths[] = $path;
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
     * Defines whether or not FileSpec will be used
     * for auto-detection
     *
     * @param boolean $flag
     *  Value to define
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function setUseFileSpec( $flag ) {

        $this -> _useFileSpec = (bool) $flag;

        return $this;
    }

    /**
     * Set a FileSpec to be used
     *
     * @param string $spec
     *  FileSpec for automatically find the proper Template View
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     *
     * @throws Next\View\ViewException
     *  Given FileSpec is invalid
     */
    public function setFileSpec( $spec ) {

         if( strpos( $spec, ':' ) === FALSE ) {
            throw ViewException::invalidSpec();
         }

         $this -> _fileSpec = $spec;

         return $this;
    }

    /**
     * Get FileSpec definition
     *
     * @return string
     *  FileSpec Definition
     */
    public function getFileSpec() {
        return $this -> _fileSpec;
    }

    // Template Variables-related Methods

    /**
     * Redefine Template Variables behavior
     *
     * @param boolean $behavior
     *  Flag to set: TRUE, they'll be returned. FALSE they'll echoed
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function setVariablesBehavior( $behavior ) {

        $this -> _returnVariables = (bool) $behavior;

        return $this;
    }

    /**
     * Get Variable Behavior
     *
     * @return boolean
     *  Template Variables behavior
     */
    public function getVariablesBehavior() {
        return $this -> _returnVariables;
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
     * @return Next\View\View
     *  View Object (Fluent Interface)
     *
     * @throws Next\View\ViewException
     *  Chosen Template Variable is defined as reserved
     */
    public function assign( $tplVar, $value = NULL ) {

        // Working recursively...

        if( is_array( $tplVar ) ) {

            foreach( $tplVar as $_tplVar => $_value ) {

                $this -> assign( $_tplVar, $_value );
            }

        } else {

            if( in_array( $tplVar, $this -> _forbiddenTplVars ) ) {

                throw ViewException::forbiddenVariable( $tplVar );
            }

            // Mapping array of values into stdClass Object

            if( is_array( $value ) ) {

                $value = Object::map( $value );
            }

            // Creating a new Template Variable

            $this -> _tplVars[ $tplVar ] = $value;
        }

        return $this;
    }

    // Page renderer

    /**
     * Set Default Template
     *
     * @param string $file
     *  Default Template View File
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function setDefaultTemplate( $file ) {

        $this -> _defaultTemplate =& $file;

        return $this;
    }

    /**
     * Get Default Template
     *
     * @return string
     *  Default Template View File
     */
    public function getDefaultTemplate() {
        return $this -> _defaultTemplate;
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
     * @return Next\HTTP\Response
     *  Response Object
     *
     * @throws Next\View\ViewException
     *  Thrown if:
     *
     *   - Unable to find Template View File
     *   - Any DataGatewayException is caught
     */
    public function render( $name = NULL, $search = TRUE ) {

        try {

            $response = $this -> _application -> getResponse();

            /**
             * @internal
             * The Template Rendering will not happen if:
             *
             * - It shouldn't, which means Next\View\View::disableRender() was called
             *
             * - Any kind of output was already sent, like a debugging purposes
             *  var_dump() or even a ControllerException which was caught
             */
            if( ! $this -> _shouldRender || ob_get_length() != 0 ) {
                return $response;
            }

            if( $search == FALSE && empty( $name ) ) {
                throw ViewException::unableToFindFile();
            }

            // Adding high priority Partial Views

            foreach( $this -> _queue as $partial ) {
                if( $partial -> getPriority() > self::PRIORITY ) $partial -> render();
            }

            // Including Template File

            ob_start();

                // ... manually

            if( ! $search ) {

                $file = stream_resolve_include_path( $name );

                if( $file !== FALSE ) {

                    include $file;

                } else {

                    throw ViewException::missingFile( $name );
                }

            } else {

                // ... or automatically

                include $this -> findFile( $name );
            }

            // Adding Main View Content to Response Body

            $response -> appendBody( ob_get_clean() );

            // Adding low priority Partial Views

            foreach( $this -> _queue as $partial ) {
                if( $partial -> getPriority() < self::PRIORITY ) $partial -> render();
            }

            /**
             * Something was rendered, so let's disallow another
             * rendering for this Request
             */
            $this -> _shouldRender = FALSE;

            return $response;

        } catch( DataGatewayException $e ) {

           throw new ViewException( $e );
        }
    }

    // Accessors

    /**
     * Disable Rendering process
     *
     * @return Next\View\View
     *  View Object (Fluent Interface)
     */
    public function disableRender() {

        $this -> _shouldRender = FALSE;

        return $this;
    }

    // OverLoading

    /**
     * Check if a Template Variable Exists
     *
     * @note The PRESENCE of key is tested, not its value
     *
     * @param string $tplVar
     *  Template Variable to be tested
     *
     * @return boolean
     *  TRUE if desired Template Variable exists and FALSE otherwise
     *
     * @throws Next\View\ViewException
     *  Testing existence of internal properties
     */
    public function __isset( $tplVar ) {

        if( substr( $tplVar, 0, 1 ) == '_' &&
                $tplVar != self::CONTROLLER_EXCEPTION ) {

            throw ViewException::unnecessaryTest();
        }

        return array_key_exists( $tplVar, $this -> _tplVars );
    }

    /**
     * Unset a Template Variable
     *
     * @note The PRESENCE of key is tested before unset it, not its value
     *
     * @param string $tplVar
     *  Template Variable to be deleted
     *
     * @throws Next\View\ViewException
     *  Trying to unset a undefined Template Variable
     *
     * @throws Next\View\ViewException
     *  Trying to unset internal properties
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
     * <p>
     *     The PRESENCE of key is tested before unset it
     *     (if <strong>$value</strong> is NULL), not its value
     * </p>
     *
     * @param string $tplVar
     *  Template Variable Name
     *
     * @param mixed|optional $value
     *  Template Variable Value
     *
     * @throws Next\View\ViewException
     *  Trying to set value for internal properties (prefixed with "_")
     */
    public function __set( $tplVar, $value = NULL ) {

        if( in_array( $tplVar, $this -> _forbiddenTplVars ) ) {
            throw ViewException::forbiddenVariable( $tplVar );
        }

        // If we don't have a value and the variable exists, we should remove it

        if( ( empty( $value ) && $value != 0 ) &&
                array_key_exists( $tplVar, $this -> _tplVars ) ) {

            unset( $this -> _tplVars[ $tplVar ] );

        } else {

            // Otherwise let's create it now

            $this -> _tplVars[ $tplVar ] = $value;
        }
    }

    /**
     * Return/Echo the current value of a Template Variable
     *
     * @note The PRESENCE of key is tested before return/echo it, not its value
     * @note Template Variable type since arrays and objects will always be returned
     *
     * @param string $tplVar]
     *
     *  Template Variable to be displayed or retrieved, accordingly to
     *   <strong>$_returnVariables</strong> property
     *
     * @return mixed|void
     *
     *   <p>
     *       If <strong>$_returnVariables</strong> property is set to TRUE
     *       or <strong>$tplVar</strong> points to an array or an Object,
     *       desired Template Variable will be returned
     *   </p>
     *
     *   <p>
     *       Otherwise, variable will be echoed and thus, nothing will
     *       be returned
     *   </p>
     *
     * @throws Next\View\ViewException
     *  Trying to access internal properties (prefixed with "_" without
     *  use their correct accessors
     *
     * @throws Next\View\ViewException
     *  Trying to get a undefined Template Variable
     */
    public function __get( $tplVar ) {

        if( property_exists( $this, $tplVar ) ) {
            throw ViewException::forbiddenAccess();
        }

        if( ! array_key_exists( $tplVar, $this -> _tplVars ) ) {
             throw ViewException::missingVariable( $tplVar );
        }

        // Should we return?

        if( $this -> _returnVariables !== FALSE ||
              ( is_array( $this -> _tplVars[ $tplVar ] ) ||
                  is_object( $this -> _tplVars[ $tplVar ] ) ) ) {

            return $this -> _tplVars[ $tplVar ];

        } else {

            // Or echo it?

            echo $this -> _tplVars[ $tplVar ];
        }
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
     * @throws Next\View\ViewException
     *  There are no directories assigned to iterate and search the file
     *
     * @throws Next\View\ViewException
     *  No file could be found
     */
    private function findFile( $file = NULL ) {

        // If we don't have a file, let's use the Default Template, if any

        $file = ( ! empty( $file ) ? $file : $this -> _defaultTemplate );

        // Cleaning dots and slashes around Template View Filename

        $file = trim( $file, './' );

        // If still empty, let's check if FileSpec detection is enabled

        if( empty( $file ) && ! $this -> _useFileSpec ) {
            throw ViewException::disabledFileSpec();
        }

        // No paths to iterate?

        if( count( $this -> _paths ) == 0 && is_null( $this -> _basepath ) )  {

            throw ViewException::noPaths( $file );
        }

        // If we don't have a Template View Name, we will use the FileSpec ability

        $file = ( ! empty( $file ) ? $file : $this -> findFileBySpec() );

        // Adding File extension, if no one was defined yet

        if( strrpos( $file, $added = sprintf( '.%s', $this -> _extension ) ) === FALSE ) {
            $file .= $added;
        }

        // Do we have more than one View Path?

        /**
         * @internal
         * Test if this separation is REALLY necessary
         */
        if( count( $this ->_paths ) == 0 ) {

            // Building the Filename

            $templateFile = sprintf( '%s/%s%s', $this -> _basepath, $this -> _subpath, $file );

            // And a cleaned version (without basepath) for possible Exceptions

            $file = sprintf( '%s%s', $this -> _subpath, $file );

            // Checking if the file exists and if it is readable

            if( file_exists( $templateFile ) && is_readable( $templateFile ) ) {

                return $templateFile;
            }

        } else {

            foreach( $this -> _paths as $path ) {

                // Building the Filename

                $templateFile = sprintf( '%s/%s/%s%s', $this -> _basepath, $path, $this -> _subpath, $file );

                // And a cleaned version (without basepath) for possible Exceptions

                $file = sprintf( '%s/%s%s', $path, $this -> _subpath, $file );

                // Checking if the file exists and if it is readable

                if( file_exists( $templateFile ) && is_readable( $templateFile ) ) {

                    return $templateFile;
                }
            }
        }

        // No file could be found, let's condition Exceptions' Messages

        if( $this -> _useFileSpec ) {

            if( ! empty( $this -> _subpath ) ) {

                throw ViewException::wrongUseOfSubpath( $file );

            } else {

                throw ViewException::unableToFindUnderFileSpec( $file );
            }

        } else {

            throw ViewException::missingFile( $file );
        }
    }

    /**
     * Find the Template View File from FileSpec
     *
     * @return string
     *  Template View FilePath from defined FileSpec
     *
     * @see
     *  Next\Controller\Router\Router::getController()
     *  Next\Controller\Router\Router::getAction()
     */
    private function findFileBySpec() {

        // Known Replacements

        $application    = $this -> _application -> getApplicationDirectory();

        $controller     = $this -> _application -> getRouter() -> getController();

        $action         = $this -> _application -> getRouter() -> getAction();

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

        // Using substr() and strpos() instead of basename() due differences produced between Windows and Linux

        $controllerBasename = substr( $controller, (int) strrpos( $controller, '\\' ) + 1 );

        $subpath = str_replace(

                       array( $application, self::CONTROLLERS_KEYWORD, $controllerBasename ),

                       '', $controller
                   );

        // Windows, Windows, Windows... <_<

        $subpath = str_replace( '\\\\', '\\', $subpath );

        $subpath= trim( $subpath, '\\' );

        // Cleaning Controller Class to find its "Real Name"

        $controller = str_replace( 'Controller', '', $controllerBasename );

        // Cleaning known Action suffixes

        $action = str_replace(

            array(

                self::ACTION_METHOD_SUFFIX_VIEW, self::ACTION_METHOD_SUFFIX_ACTION
            ),

            '', $action
        );

        // Replacing known matches

        $spec = trim(

                    str_replace(

                        array(
                            self::APPLICATION, self::CONTROLLER, self::ACTION, self::SUBPATH
                        ),

                        array(
                            $application, $controller, $action, $subpath
                        ),

                        $this -> _fileSpec ),

                    '/' );

        $spec = Tools::cleanAndInvertPath( $spec );

        return sprintf( '%s.%s', strtolower( $spec ), $this -> _extension );
    }
}