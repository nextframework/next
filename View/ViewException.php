<?php

namespace Next\View;

use Next\Components\Object;    # Object Class;

/**
 * View Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ViewException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x0000085E, 0x00000890 );

    /**
     * Invalid Partial View Object
     *
     * @var integer
     */
    const INVALID_PARTIAL       =    0x0000085E;

    /**
     * Invalid View Priority
     *
     * @var integer
     */
    const INVALID_PRIORITY      =    0x0000085E;

    /**
     * Invalid FileSpec
     *
     * @var integer
     */
    const INVALID_SPEC          =    0x0000085F;

    /**
     * Invalid Template Variable Name
     *
     * @var integer
     */
    const FORBIDDEN_VARIABLE    =    0x00000860;

    /**
     * Invalid Escape Callback
     *
     * @var integer
     */
    const INVALID_CALLBACK      =    0x00000861;

    /**
     * Template View Rendering Failure
     *
     * @var integer
     */
    const RENDER_FAILURE        =    0x00000862;

    /**
     * Unnecessary Tests
     *
     * @var integer
     */
    const UNNECESSARY_TEST      =    0x00000863;

    /**
     * Missing Template Variable Name
     *
     * @var integer
     */
    const MISSING_VARIABLE      =    0x00000864;

    /**
     * Forbidden Access
     *
     * @var integer
     */
    const FORBIDDEN_ACCESS      =    0x00000865;

    /**
     * Disabled FileSpec
     *
     * @var integer
     */
    const DISABLED_FILESPEC     =    0x00000866;

    /**
     * No paths to search
     *
     * @var integer
     */
    const NO_PATHS              =    0x00000867;

    /**
     * Wrong use of subpath
     *
     * @var integer
     */
    const SUBPATH_MISUSE        =    0x00000868;

    /**
     * Unable to Find File
     *
     * @var integer
     */
    const UNABLE_TO_FIND        =    0x00000869;

    /**
     * Missing Template View File
     *
     * @var integer
     */
    const MISSING_FILE          =    0x0000086A;

    // Exception Messages

    /**
     * Invalid Partial View
     *
     * @param Next\Components\Object $object
     *   Object trying to be used as Partial View
     *
     * @return Next\View\ViewException
     *   Exception for invalid Partial View Object
     */
    public static function invalidPartial( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Partial View.

            <br />

            Partial Views must implement View Interface',

            self::INVALID_PARTIAL,

            (string) $object
        );
    }

    /**
     * Invalid Partial View Priority
     *
     * @param Next\View\View $partialView
     *   Partial View Object
     *
     * @param integer $priority
     *   Partial View Priority
     *
     * @return Next\View\ViewException
     *   Exception for invalid Partial View Priority
     */
    public static function invalidPriority( View $partialView, $priority ) {

        return new self(

            'Partial View <strong>%s</strong> has an invalid Priority
            (<strong>%d</strong>).

            <br />

            Partial Views Priorities must be greater than zero and must not

            conflict with Main View Priority (<strong>%d</strong>).',

            self::INVALID_PRIORITY,

            array( (string) $partialView, $priority, \Next\View::PRIORITY )
        );
    }

    /**
     * Invalid FileSpec defined
     *
     * @return Next\View\ViewException
     *   Exception for invalid FileSpec
     */
    public static function invalidSpec() {

        return new self(

            'Invalid File Path Spec format',

            self::INVALID_SPEC
        );
    }

    /**
     * Forbidden Template Variable Name
     *
     * @param string $tplVar
     *   Desired Template Variable Name
     *
     * @return Next\View\ViewException
     *   Exception for forbidden variable name
     */
    public static function forbiddenVariable( $tplVar ) {

        return new self(

            'The name <strong>%s</strong> is a Reserved Template Variable Name',

            self::FORBIDDEN_VARIABLE,

            $tplVar
        );
    }

    /**
     * Template View Rendering Failure
     *
     * Template View File could not be manually rendered and the
     * auto-searching feature disabled
     *
     * @return Next\View\ViewException
     *   Exception for Template View File findability failure
     */
    public static function unableToFindFile() {

        return new self(

            'You have to enter the full filepath of Template View File or activate the auto-search.',

            self::RENDER_FAILURE
        );
    }

    /**
     * Unnecessary Tests
     *
     * Reserved Template Variables are ALWAYS present.
     * If you're testing its presence, you're doing it wrong
     *
     * @return Next\View\ViewException
     *   Exception for unnecessary tests being made
     */
    public static function unnecessaryTest() {

        return new self(

            'You shouldn\'t test if a Reserved Template Variable exists',

            self::UNNECESSARY_TEST
        );
    }

    /**
     * Missing Template Variable
     *
     * If you receive this error, you're doing it wrong
     *
     * @param string $tplVar
     *   Desired Template Variable Name
     *
     * @return Next\View\ViewException
     *   Exception for missing Template Variable being used
     */
    public static function missingVariable( $tplVar ) {

        return new self(

            'Template Var <strong>%s</strong> doesn\'t exist',

            self::MISSING_VARIABLE,

            $tplVar
        );
    }

    /**
     * Forbidden Access
     *
     * Template Variables SHOULD not have the same name of Internal Properties
     * to not cause confusion.
     *
     * @return Next\View\ViewException
     *   Exception for Template Variable use forbiddenness
     */
    public static function forbiddenAccess() {

        return new self(

            'Internal Properties cannot be accessed as Template Variables',

            self::FORBIDDEN_ACCESS
        );
    }

    /**
     * Disabled FileSpec
     *
     * Template View File could not be manually rendered and without
     * FileSpec, auto-search cannot work
     *
     * @return Next\View\ViewException
     *   Exception for FileSpec inactivity when no Template View name is provided
     */
    public static function disabledFileSpec() {

        return new self(

            'Without a Template View Filepath and Detection by FileSpec deactivated, we are unable to find a Template View File automatically',

            self::DISABLED_FILESPEC
        );
    }

    /**
     * No Paths to search for Template View Files
     *
     * @param string $filename
     *   Template View Filename
     *
     * @return Next\View\ViewException
     *   Exception for impossibility to find a Template View File when no paths were provided
     */
    public static function noPaths( $filename ) {

        return new self(

            'No Views\'s Directories assigned.

            <br />

            Unable to find a Template View File matching <strong>%s</strong>',

            self::NO_PATHS,

            $filename
        );
    }

    /**
     * Wrong use of Subpaths
     *
     * We still not able to find a proper Template View File.
     * Are you sure this Subpath is correctly defined?
     *
     * @param string $file
     *   File we could not find
     *
     * @return Next\View\ViewException
     *   Exception for subpaths being wrongly used
     */
    public static function wrongUseOfSubpath( $file ) {

        return new self(

            'Usually when using FileSpec Detection you don\'t need to use Subpaths.

            <br />

            Please check this value in order to fix possible mistakes

            <br />

            The file we were trying to locate is <strong>%s</strong>, starting
            from defined basepath',

            self::SUBPATH_MISUSE, $file
        );
    }

    /**
     * Unable to find a Template View in defined FileSpec
     *
     * @param string $file
     *   File we could not find
     *
     * @return Next\View\ViewException
     *   Exception for impossibility to find Template View File
     */
    public static function unableToFindUnderFileSpec( $file ) {

        return new self(

            'Unable to find a Template View File under specified FileSpec.

            <br />

            The file we were trying to locate is <strong>%s</strong>, starting
            from defined basepath',

            self::UNABLE_TO_FIND, $file
        );
    }

    /**
     * No such File
     *
     * That's it! We REALLY can't find a Template View File
     *
     * @param string $file
     *   File we could not find
     *
     * @return Next\View\ViewException
     *   Exception for missing Template View File
     */
    public static function missingFile( $file ) {

        return new self(

            'Unable to find a Template View for <strong>%s</strong>',

            self::MISSING_FILE, $file
        );
    }
}