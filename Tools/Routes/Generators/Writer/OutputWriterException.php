<?php

/**
 * Routes Generator Output Writer Exception Class | Tools\Routes\Generators\Writer\OutputWriterException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Tools\Routes\Generators\Writer;

/**
 * Routes Generator Output Writer Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class OutputWriterException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000792, 0x000007C4 );

    /**
     * Missing required option
     *
     * @var integer
     */
    const MISSING_OPTION =    0x00000792;

    /**
     * Recording Failure
     *
     * @var integer
     */
    const RECORD_FAILURE      =    0x00000797;

    // Exception Messages

    /**
     * Missing required configuration option
     *
     * A required configuration option was not defined or
     * overwrote a default options with an invalid value
     *
     * @param string $option
     *  The required configuration option
     *
     * @return \Next\Tools\Routes\Generators\GeneratorsException
     *  Exception for a missing required configuration
     */
    public static function missingConfigurationOption( $option ) {

        return new self(

            'Configuration option <strong>%s</strong> was not
            informed or has an invalid value',

            self::MISSING_OPTION, $option
        );
    }

    /**
     * Something went wrong when trying to record found Routes
     *
     * @param array $args
     *  Variable list of arguments to build final message
     *
     * @return \Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Exception for Routes writability failure
     */
    public static function recordingFailure( array $args ) {

        return new self(

            '<h1>Route Recording Failure</h1>

            <strong>Route:</strong> %s

            <strong>Class:</strong> %s

            <strong>Method:</strong> %s

            <strong>Reason:</strong> %s',

            self::RECORD_FAILURE,

            $args
        );
    }
}
