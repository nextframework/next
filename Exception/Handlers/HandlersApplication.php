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

use Next\Application\AbstractApplication;    # Abstract Application Class
use Next\View\Standard as View;              # View Engine

/**
 * A \Next\Application\Application for our custom Error and Exception Handlers
 *
 * @package    Next\Exception
 *
 * @uses       Next\Application\AbstractApplication, Next\View\Standard
 */
class HandlersApplication extends AbstractApplication {

    /**
     * View Engine Setup
     */
    public function setupView() {

        return new View(
            [
                'application' => $this,
                'basepath'    => sprintf( '%s/Views', __DIR__ )
            ]
        );
    }

    // Abstract Methods Implementation

    /**
     * Controllers Setup
     *
     * It's not needed because there is no routing for this Application
     */
    protected function setupControllers() {}
}
