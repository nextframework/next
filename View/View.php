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

use Next\HTTP\Response;    # HTTP Response Class

/**
 * An Interface for all View Engines
 *
 * @package    Next\View
 *
 * @uses       Next\HTTP\Response
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
    public function resetDefaults() : View;

    // View Helper-related Methods

    /**
     * Register a new Template View Helper
     *
     * @param string $name
     *  Template View Helper name, through which it'll be accessed
     *  within Template Views
     *
     * @param Next\View\Helpers\Helper|string|callable $resource
     *  Resource being registered as a View Helper.
     *  It can be a Fully Qualified Classname of an Object implementing
     *  *Next\View\Helper\Helper*
     *  Or it can be any other callable resource as well, like arrays, anonymous
     *  function, an internal function...
     */
    public function registerHelper( $name, $classname ) : View;

    /**
     * Get all registered View Helpers
     */
    public function getHelpers() : array;

    // Views File-related Methods

    /**
     * Get default File Extension
     */
    public function getExtension() :? string;

    // Views Path-related Methods

    /**
     * Get current Basepath
     */
    public function getBasepath() :? string;

    /**
     * Get current Subpath
     */
    public function getSubpath() :? string;

    /**
     * Add more paths to Template Files Location
     *
     * @param string $path
     *  Another Path to locate Template Views
     */
    public function addPath( $path ) : View;

    /**
     * Get current paths
     */
    public function getPaths() : array;

    /**
     * Get FileSpec definition
     */
    public function getFileSpec() :? string;

    // Template Variables-related Methods

    /**
     * Should Variables be returned or echoed
     */
    public function shouldReturnVariables() : bool;

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
    public function assign( $tplVar, $value = NULL ) : View;

    /**
     * Get all assigned Template Variables
     */
    public function getVars() : array;

    /**
     * Get an specific Template Variable assigned
     *
     * @param string $tplVar
     *  The Template Variable
     */
    public function getVar( $tplVar );

    // Template Rendering-related Methods

    /**
     * Disable Rendering Process
     */
    public function disableRender() : View;

    /**
     * (Re-)Enables Rendering process
     */
    public function enableRender() : View;

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
    public function render( $name = NULL, $search = TRUE ) : Response;

    /**
     * Get Default Template
     */
    public function getDefaultTemplate() :? string;
}
