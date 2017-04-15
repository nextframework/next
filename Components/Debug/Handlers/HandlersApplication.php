<?php

namespace Next\Components\Debug\Handlers;

use Next\Application\AbstractApplication;           # Abstract Application Class
use Next\View\Standard as View;                     # View Engine
use Next\Controller\Router\NullRouter as Router;    # Standard Router Class

/**
 * Errors and Exception Handlers Application Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class HandlersApplication extends AbstractApplication {

    /**
     * View Engine Setup
     */
    public function setupView() {

        $this -> view = new View( $this );

        $this -> view -> setBasepath( sprintf( '%s/Views', dirname( __FILE__ ) ) );
    }

    // Application Interface Method Implementation

    /**
     * Router Setup
     */
    public function setupRouter() {
        $this -> router = new Router( $this );
    }

    // Abstract Methods Implementation

    /**
     * Controllers Setup
     *
     * It's not needed because there is no routing for this Application
     */
    protected function setupControllers() {}
}
