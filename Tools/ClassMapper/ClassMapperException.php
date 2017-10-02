<?php

/**
 * Class Mapper Exception Class | Tools\ClassMapper\ClassMapperException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\ClassMapper;

/**
 * ClassMapper Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ClassMapperException extends \Next\Exception\Exception {

    /**
     * Unknown Mapping Strategy
     *
     * @var integer
     */
    const UNKNOWN                  = 0x0000075F;

    /**
     * No Output Directory
     *
     * @var integer
     */
    const NO_OUT_DIR               = 0x00000760;

    /**
     * Invalid Output Directory
     *
     * @var integer
     */
    const INVALID_OUT_DIR          = 0x00000761;

    /**
     * Unwritable Output Directory
     *
     * @var integer
     */
    const UNWRITABLE_OUT_DIR       = 0x00000762;

    /**
     * Missing Output Filename
     *
     * @var integer
     */
    const NO_OUT_NAME              = 0x00000763;

    /**
     * Unknown Mapping Strategy
     *
     * @return \Next\Tools\ClassMapper\ClassMapperException
     *  Exception for invalid mapping output format
     */
    public static function unknown() {

        return new self(

            'Invalid output format',

            self::UNKNOWN
        );
    }

    /**
     * Output Directory is not set
     *
     * @return \Next\Tools\ClassMapper\ClassMapperException
     *  Exception for missing Output Directory
     */
    public static function noOutputDirectory() {

        return new self(

            'You must enter a non-empty string for output directory',

            self::NO_OUT_DIR
        );
    }

    /**
     * Invalid Output Directory
     *
     * @param string $directory
     *  Output Directory
     *
     * @return \Next\Tools\ClassMapper\ClassMapperException
     *  Exception for Output Directory invalidity
     */
    public static function invalidOutputDirectory( $directory ) {

        return new self(

            'Output directory <strong>%s</strong> doesn\'t exist or it\'s not a directory',

            self::INVALID_OUT_DIR,

            $directory
        );
    }

    /**
     * Output Directory is not writable
     *
     * @param string $directory
     *  Output Directory
     *
     * @return \Next\Tools\ClassMapper\ClassMapperException
     *  Exception for Output Directory unwritability
     */
    public static function unwritableOutputDirectory( $directory ) {

        return new self(

            'Output directory <strong>%s</strong> is not writable',

            self::UNWRITABLE_OUT_DIR,

            $directory
        );
    }

    /**
     * Missing Output Filename
     *
     * @return \Next\Tools\ClassMapper\ClassMapperException
     *  Exception for missing Output Filename
     */
    public static function missingOutputFilename() {

        return new self(

            'Output filename must be set as non-empty string',

            self::NO_OUT_NAME
        );
    }
}
