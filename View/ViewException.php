<?php

/**
 * View Engines Exception Class | View\ViewException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\View;

/**
 * Exception Class(es)
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\AccessViolationException;

use Next\Components\Object;    # Object Class;

/**
 * View Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ViewException extends Exception {

    // Exception Messages

    /**
     * Chosen Template Variable Name is reserved or forbidden due
     * an associated View Engine Helper
     *
     * @param string $tplVar
     *  Desired Template Variable Name
     *
     * @return \Next\Exception\Exceptions\InvalidArgumentException
     *  Exception for forbidden variable name
     */
    public static function forbiddenVariable( $tplVar ) {

        return new InvalidArgumentException(

            sprintf(

                '<strong>%s</strong> is a reserved
                Template Variable name or it has a View Helper
                associated to it and, therefore, cannot be used
                for assignment',

                $tplVar
            )
        );
    }

    /**
     * Template View File could not be manually found and the
     * auto-searching feature by FileSpec is disabled
     *
     * @return \Next\Exception\Exceptions\RuntimeException
     *  Exception for Template View File findability failure
     */
    public static function unableToFindFile() {

        return new RuntimeException(

            'Template File could not be found.

            You must enter the full filepath of a Template View File
            to be rendered or, if you\'re using Template FileSpec,
            activate the auto-search.'
        );
    }

    /**
     * Template View File could not be found
     *
     * @param string $file
     *  File we could not find
     *
     * @return \Next\Exception\Exceptions\RuntimeException
     *  Exception for missing Template View File
     */
    public static function missingFile( $file ) {

        return new RuntimeException(

            sprintf(
                'Unable to find a valid Template View for filepath
                <strong>%s</strong>', $file
            )
        );
    }

    /**
     * Missing Template Variable
     *
     * @param string $tplVar
     *  Desired Template Variable Name
     *
     * @return \Next\Exception\Exceptions\AccessViolationException
     *  Exception for missing Template Variable being used
     */
    public static function missingVariable( $tplVar ) {

        return new AccessViolationException(

            sprintf(

                'Template Var <strong>%s</strong> doesn\'t exist',

                $tplVar
            )
        );
    }

    /**
     * Unknown View Helper
     *
     * @param string $helper
     *  Helper trying to be used
     *
     * @return \Next\Exception\Exceptions\InvalidArgumentException
     *  Exception for unknown View Helper
     */
    public static function unknownHelper( $helper ) {

        return new InvalidArgumentException(

            sprintf(
                'Unknown View Helper <strong>%s</strong>', $helper
            )
        );
    }

    /**
     * Template View File could not be manually found and with the
     * Template FileSpec auto-searching feature disabled, a
     * Template File can't be automatically found
     *
     * @return \Next\Exception\Exceptions\RuntimeException
     *  Exception for when no Template View name is provided and
     *  auto-search by Template View FileSpec is disabled
     */
    public static function disabledFileSpec() {

        return new RuntimeException(

            'Without a Template View Filepath and auto-search by
            FileSpec deactivated, we can\'t find a Template View File
            automatically'
        );
    }

    /**
     * No Template View Paths to search for Template View Files
     *
     * @param string $filename
     *  Template View Filename
     *
     * @return \Next\Exception\Exceptions\RuntimeException
     *  Exception for impossibility to find a Template View File
     *  when no paths were provided
     */
    public static function noPaths( $filename ) {

        return new RuntimeException(

            sprintf(

                'Unable to find a Template View File matching
                <strong>%s</strong> because no Template View Paths
                were defined',

                $filename
            )
        );
    }

    /**
     * Template View Files could not be automatically found using the
     * Template FileSpec auto-searching feature and a Template Subpath
     * is defined.
     *
     * Just a split condition to help Developers debug
     *
     * @param string $file
     *  File we could not find
     *
     * @return \Next\Exception\Exceptions\RuntimeException
     *  Exception for subpaths being wrongly used
     */
    public static function wrongUseOfSubpath( $file ) {

        return new RuntimeException(

            sprintf(

                'Unable to find a Template View File matching <strong>%s</strong>

                Usually, when using Template View FileSpec auto-search
                feature there\'s no need to use Template Subpaths.

                Check the value in order to fix possible mistakes.',

                $file
            )
        );
    }

    /**
     * Unable to find a Template View in defined FileSpec
     *
     * @param string $file
     *  File we could not find
     *
     * @return \Next\Exception\Exceptions\RuntimeException
     *  Exception for impossibility to find Template View File
     */
    public static function unableToFindUnderFileSpec( $file ) {

        return new RuntimeException(

            sprintf(

                'Unable to find a Template View File matching <strong>%s</strong>',

                $file
            )
        );
    }
}