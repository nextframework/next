<?php

namespace Next\Tools;

use Next\Tools\RoutesGenerator\RoutesGenerator as Generator;    # Routes Generator Interface

/**
 * Routes Generator Tool
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class RoutesGenerator {

    /**
     * Routes Generator Strategy
     *
     * @var Next\Tools\RoutesGenerator\RoutesGenerator $generator
     */
    private $generator;

    /**
     * Routes Generator Constructor
     *
     * @param Next\Tools\RoutesGenerator\RoutesGenerator $generator
     *  Routes Generator Strategy
     */
    public function __construct( Generator $generator ) {

        $this -> generator =& $generator;
    }

    /**
     * Start Generation Process
     *
     * @param boolean|optional $format
     *  Flag to condition whether or not a format action will be done
     *
     * E.g.: If RoutesGenerator Strategy uses a Database (Standard),
     * all the records in it will be deleted first.
     */
    public function run( $format = FALSE ) {

        if( $format !== FALSE ) {

            $this -> generator -> reset();
        }

        $this -> generator -> find() -> save();
    }
}