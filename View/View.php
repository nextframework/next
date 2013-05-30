<?php

namespace Next\View;

/**
 * View Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface View {

    // Directory Constants

    /**
     * Controllers Keyword
     *
     * @var string
     */
    const CONTROLLERS_KEYWORD   = 'Controllers';

    /**
     * Action Method 'View' Suffix
     *
     * According to Next Framework naming forms this suffix is appended
     * in controllers' action methods describing their purpose
     * to only for display something
     *
     * @var string
     */
    const ACTION_METHOD_SUFFIX_VIEW      = 'View';

    /**
     * Action Method 'View' Suffix
     *
     * According to Next Framework naming forms this suffix is appended
     * in controllers' action methods describing their purpose to execute
     * some task (like a database interaction) before display something
     *
     * @var string
     */
    const ACTION_METHOD_SUFFIX_ACTION    = 'Action';

    /**
     * Application FileSpec Token
     *
     * @var string
     */
    const APPLICATION             = ':application';

    /**
     * Controller FileSpec Token
     *
     * @var string
     */
    const CONTROLLER              = ':controller';

    /**
     * Action FileSpec Token
     *
     * @var string
     */
    const ACTION                  = ':action';

    /**
     * Subpath FileSpec Token
     *
     * @var string
     */
    const SUBPATH                 = ':subpath';

    /**
     * Controller Exception Keyword
     *
     * @var string
     */
    const CONTROLLER_EXCEPTION    = '__EXCEPTION__';

    /**
     * Reset View to its defaults
     *
     * User defined Template Variables are removed leaving only the
     * pre-assigned variables.
     *
     * If, for some reason, it's already empty, simply create them.
     */
    public function resetDefaults();

    // Composite Views-related Methods

    /**
     * Add a new Composite View to be rendered
     *
     * @param Next\View\View $view
     *   Composite View to be added
     *
     * @param integer $priority
     *
     *   Priority of the Composite View
     *
     *   Priorities higher than this class priority,
     *   will be prepended to Response.
     *
     *   Priorities lower than this class priority,
     *   will be appended to Response
     */
    public function addView( View $view, $priority = 0 );

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
     *   Partial View Priority
     */
    public function setPriority( $priority );

    /**
     * Get View Priority
     */
    public function getPriority();

    /**
     * Get Composite Views Queue Object
     */
    public function getCompositeQueue();

    // Views File-related Methods

    /**
     * Set a new default File Extension
     *
     * If the file extension is not specified in render() call, it will be added
     * automatically
     *
     * @param string $extension
     *   Template ViewS File Extensions
     */
    public function setExtension( $extension ) ;

    /**
     * Get default File Extension
     */
    public function getExtension();

    // Views Path-related Methods

    /**
     * Set a basepath to be prepended in Template Files Location
     *
     * @param string $path
     *   Template Views Bsepath
     */
    public function setBasepath( $path );

    /**
     * Get current Basepath
     */
    public function getBasepath();

    /**
     * Subpath for Template Views Files
     *
     * Subpaths are the portion of Template Views File Paths which is common in all
     * render() calls in each Controller.
     *
     * Setting this, makes unnecessary repeat this "prefix" in every Template View Filename
     *
     * The subpath will be inserted between BasePath and current path values
     * (from $paths), during iteration
     *
     * If empty, the subpath will be removed, avoiding a malformed filepath
     *
     * Note: Even using :subpath in FileSpec, sometimes, you have to use a manual SubPath too,
     *       specially when your Template Files are in a different directory levels than your Controllers
     *
     * @param string $path
     *   Template Views Optional Subpath
     */
    public function setSubpath( $path );

    /**
     * Get current Subpath
     */
    public function getSubpath();

    /**
     * Add more paths to Template Files Location
     *
     * @param string $path
     *   Another Path to locate Template Views
     */
    public function addPath( $path );

    /**
     * Get current paths
     */
    public function getPaths();

    /**
     * Defines whether or not FileSpec will be used
     * for auto-detection
     *
     * @param boolean $flag
     *   Value to define
     */
    public function setUseFileSpec( $flag );

    /**
     * Set a FileSpec to be used
     *
     * @param string $spec
     *   FileSpec for automatically find the proper Template View
     */
    public function setFileSpec( $spec );

    /**
     * Get FileSpec definition
     */
    public function getFileSpec() ;

    // Template Variables-related Methods

    /**
     * Redefine Template Variables behavior
     *
     * @param boolean $behavior
     *   Flag to set: TRUE, they'll be returned. FALSE they'll echoed
     */
    public function setVariablesBehavior( $behavior );

    /**
     * Get Variable Behavior
     */
    public function getVariablesBehavior();

    /**
     * Assign one or more Template Variables
     *
     * @param string|array $tplVar
     *   Template Variable Name or an array of Variables
     *
     * @param mixed|optional $value
     *
     *   <p>
     *       Value for Template Variable when <strong>$tplVar</strong>
     *       is not an array
     *   </p>
     */
    public function assign( $tplVar, $value = NULL );

    // Page renderer

    /**
     * Set Default Template
     *
     * @param string $file
     *  Default Template View File
     */
    public function setDefaultTemplate( $file );

    /**
     * Get Default Template
     */
    public function getDefaultTemplate();


    // Page Render

    /**
     * Render the page, outputs the buffer
     *
     * @param string|optional $name
     *   Template View File to render. If NULL, we'll try to find it
     *
     * @param boolean|optional $search
     *   Flag to condition whether or not we should try to find the
     *   proper Template View File automatically
     */
    public function render( $name = NULL, $search = TRUE );

    // Accessors

    /**
     * Disable Rendering Process
     */
    public function disableRender();
}
