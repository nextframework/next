<?php

/**
 * View Engines Interface | View\View.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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
    const CONTROLLERS_KEYWORD            = 'Controllers';

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
    const APPLICATION                    = ':application';

    /**
     * Controller FileSpec Token
     *
     * @var string
     */
    const CONTROLLER                     = ':controller';

    /**
     * Action FileSpec Token
     *
     * @var string
     */
    const ACTION                         = ':action';

    /**
     * Subpath FileSpec Token
     *
     * @var string
     */
    const SUBPATH                        = ':subpath';

    /**
     * Reset View to its defaults
     *
     * User defined Template Variables are removed leaving only the
     * pre-assigned variables.
     *
     * If, for some reason, it's already empty, simply create them.
     */
    public function resetDefaults();

    // View Helper-related Methods

    /**
     * Register a new Template View Helper
     *
     * @param \Next\View\Helper\Helper|string $helper
     *  Template View Helper
     */
    public function registerHelper( $helper );

    // Views File-related Methods

    /**
     * Get default File Extension
     */
    public function getExtension();

    // Views Path-related Methods

    /**
     * Get current Basepath
     */
    public function getBasepath();

    /**
     * Get current Subpath
     */
    public function getSubpath();

    /**
     * Add more paths to Template Files Location
     *
     * @param string $path
     *  Another Path to locate Template Views
     */
    public function addPath( $path );

    /**
     * Get current paths
     */
    public function getPaths();

    /**
     * Get FileSpec definition
     */
    public function getFileSpec() ;

    // Template Variables-related Methods

    /**
     * Get Variable Behavior
     */
    public function getVariablesBehavior();

    /**
     * Assign one or more Template Variables
     *
     * @param string|array $tplVar
     *  Template Variable Name or an array of Variables
     *
     * @param mixed|optional $value
     *
     *   <p>
     *       Value for Template Variable when <strong>$tplVar</strong>
     *       is not an array
     *   </p>
     */
    public function assign( $tplVar, $value = NULL );

    /**
     * Get all assigned Template Variables
     */
    public function getVars();

    /**
     * Get an specific Template Variable assigned
     *
     * @param string $tplVar
     *  The Template Variable
     */
    public function getVar( $tplVar );

    // Page renderer

    /**
     * Get Default Template
     */
    public function getDefaultTemplate();


    // Page Render

    /**
     * Render the page, outputs the buffer
     *
     * @param string|optional $name
     *  Template View File to render. If NULL, we'll try to find it
     *
     * @param boolean|optional $search
     *  Flag to condition whether or not we should try to find the
     *  proper Template View File automatically
     */
    public function render( $name = NULL, $search = TRUE );

    // Accessors

    /**
     * Disable Rendering Process
     */
    public function disableRender();

    /**
     * (Re-)Enables Rendering process
     */
    public function enableRender();
}
