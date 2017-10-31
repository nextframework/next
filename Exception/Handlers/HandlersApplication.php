<?php

/**
 * Errors and Exception Handlers Application Class | Exception\Handlers\HandlersApplication.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Handlers;

use Next\View\View;                     # View Interface
use Next\Application\Application;       # Application Abstract Class
use Next\View\Standard;                 # View Engine
use Next\Session\Manager as Session;    # Session Manager Class

/**
 * An Application for our custom Error and Exception Handlers
 *
 * @package    Next\Exception
 *
 * @uses       Next\Application\Application
 *             Next\HTTP\Router\NullRouter
 *             Next\View\Standard
 *             Next\Session\Manager
 */
class HandlersApplication extends Application {

    /**
     * View Engine Setup
     *
     * @return \Next\View\Standard
     *  View Engine Object configured for Exceptions Handler Application
     */
    public function setupView() : View {

        return new Standard(
            [
                'application' => $this,
                'basepath'    => sprintf( '%s/Views', __DIR__ )
            ]
        );
    }
}
