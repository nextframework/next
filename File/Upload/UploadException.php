<?php

/**
 * File Upload Exception Class | File\Upload\UploadException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Upload;

use Next\Components\Object;    # Object Class

/**
 * Upload Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class UploadException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000396, 0x000003C8 );

    /**
     * Invalid Chain Post-Processor
     *
     * @var integer
     */
    const INVALID_CHAIN_POST_PROCESSOR    = 0x00000396;

    /**
     * Nothing to Upload
     *
     * @var integer
     */
    const NOTHING_TO_UPLOAD               = 0x00000397;

    /**
     * Exceeded number of concurrent files
     *
     * @var integer
     */
    const EXCEEDED_CONCURRENT             = 0x00000398;

    /**
     * Invalid Chain Post-Processor
     *
     * Given Object is not a valid Post-Processor because it doesn't
     * implements neither \Next\File\Upload\PostProcessor\PostProcessor
     * and/or \Next\Components\Interfaces\Informational interfaces
     *
     * @param \Next\Components\Object $object
     *  Object used as Post-Processor
     *
     * @return \Next\File\Upload\UploadException
     *  Exception for Invalid Post-Processors
     */
    public static function invalidChainPostProcessor( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Post-Processor for the Chain

            <br /><br />

            In a Chain, Post-Processors must implement both Post-Processor and Informational interfaces',

            self::INVALID_CHAIN_POST_PROCESSOR,

            (string) $object
        );
    }

    /**
     * Nothing to Upload
     *
     * @return \Next\File\Upload\UploadException
     *  Exception for when there is nothing to upload
     */
    public static function nothingToUpload() {
        return new self( 'Nothing to upload' , self::NOTHING_TO_UPLOAD );
    }

    /**
     * Exceeded number of concurrent files
     *
     * @return \Next\File\Upload\UploadException
     *  Exception for when the number of concurrent files were exceeded
     */
    public static function concurrentFilesLimit() {
        return new self( 'Exceeded number of concurrent files' , self::EXCEEDED_CONCURRENT );
    }
}
