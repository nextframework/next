<?php

/**
 * Routes Generators, Generators Exception Class | Tools\Generators\GeneratorsException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Tools\Routes\Generators;

/**
 * Routes Generators Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class GeneratorsException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000792, 0x000007C4 );

    /**
     * No Routes Found
     *
     * @var integer
     */
    const NO_ROUTES           =    0x00000792;

    /**
     * Invalid Route Structure
     *
     * @var integer
     */
    const INVALID_ROUTE       =    0x00000793;

    /**
     * Mal-formed Route
     *
     * @var integer
     */
    const MALFORMED_ROUTE     =    0x00000794;

    /**
     * Duplicated Route
     *
     * @var integer
     */
    const DUPLICATED_ROUTE    =    0x00000795;

    /**
     * Invalid Route Arguments Structure
     *
     * @var integer
     */
    const INVALID_ARGS        =    0x00000796;

    /**
     * Recording Failure
     *
     * @var integer
     */
    const RECORD_FAILURE      =    0x00000797;

    // Exception Messages

    /**
     * No Routes found
     *
     * This is not entirely unnecessary because also enforces the use of final keyword
     * to distinguish Action Methods from Common Methods
     *
     * @param array $args
     *  Variable list of arguments to build final message
     *
     * @return \Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Exception for an Action Methods without Routes
     */
    public static function noRoutes( array $args ) {

        return new self(

            '<h1>No Routes Found</h1>

            An Action Method must have at least one Route defined

            Class: %s

            Method: %s',

            self::NO_ROUTES,

            $args
        );
    }

   /**
     * Defined Route URI is invalid because lacks the minimum
     * required components
     *
     * @param array $args
     *  Variable list of arguments to build final message
     *
     * @return \Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Exception for conceptual invalid Route structure
     */
    public static function invalidRouteStructure( array $args ) {

        return new self(

            '<h1>Invalid Route</h1>

            Routes must be composed of at least two components: Request method and URI Route!

            Route: %s

            Class: %s

            Method: %s',

            self::INVALID_ROUTE,

            $args
        );
    }

    /**
     * Defined Route URI is mal-formed because violates hierarchy concepts
     *
     * @param array $args
     *  Variable list of arguments to build final message
     *
     * @return \Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Exception for malformed Routes
     */
    public static function malformedRoute( array $args ) {

        return new self(

            '<h1>Invalid Route</h1>

            Routes defined as a single slash cannot have params, as a
            hierarchy logic should be followed!

            Route: %s

            Class: %s

            Method: %s',

            self::MALFORMED_ROUTE,

            $args
        );
    }

    /**
     * Define Route URI was already assigned to another Controller Action
     *
     * @param array $args
     *  Variable list of arguments to build final message
     *
     * @return \Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Exception for duplicated Routes
     */
    public static function duplicatedRoute( array $args ) {

        return new self(

            '<h1>Duplicated Route</h1>

            <strong>Request Method:</strong> %s

            <strong>Route:</strong> %s

            <strong>Class:</strong> %s

            <strong>Method:</strong> %s',

            self::DUPLICATED_ROUTE,

            $args
        );
    }

    /**
     * Defined Route Arguments is invalid because lacks the minimum
     * required components
     *
     * @param array $args
     *  Variable list of arguments to build final message
     *
     * @return \Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Exception for conceptual invalid Route Arguments structure
     */
    public static function malformedArguments( array $args ) {

        return new self(

            '<h1>Invalid Route Arguments</h1>

            Routes Arguments must be composed of at least two components: Argument Name and Type

            Class: %s

            Method: %s',

            self::INVALID_ARGS,

            $args
        );
    }
}
