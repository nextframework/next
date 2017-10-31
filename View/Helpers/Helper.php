<?php

/**
 * View Helper Interface | View\Helper\Helper.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\View\Helpers;

/**
 * An Interface for all View Engines' Helpers
 *
 * @package    Next\View
 */
interface Helper {

    /**
     * Executes the Helper Object as if it was a function, shortening their
     * usage in Template Views
     *
     * @param array $args
     *  Arguments passed from Template Views to the implementation that leads
     *  the Request Flow here (i.e `Next\View\Standard::call()`)
     */
    public function __invoke( array $args ) : string;
}