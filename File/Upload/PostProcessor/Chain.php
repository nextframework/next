<?php

/**
 * File Upload Post-Processing Chain Class | File\Upload\PostProcessor\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Upload\PostProcessor;

use Next\Components\Interfaces\Informational;          # Informational Interface

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Post-Processor Chain
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Chain extends AbstractCollection {

    /**
     * Executes all Post-Processors added to the Chain
     *
     * @param mixed $data
     *  Data to process
     *
     * @return boolean|\Next\File\Upload\PostProcessor\PostProcessor
     *  TRUE if valid by all Post-Processors and the Post-Processor Object otherwise
     */
    public function process( $data ) {

        if( count( $this ) == 0 ) return TRUE;

        foreach( $this -> getIterator() as $processor ) {

            if( ! $processor -> execute( $data ) ) return $processor;
        }

        return TRUE;
    }

    // Abstract Method Implementation

    /**
     * Check Object acceptance
     *
     * Check if given Post-Processor is acceptable in Post-Processors Chain
     * To be valid, the Post-Processor must implement both \Next\File\Upload\PostProcessor
     * and \Next\Components\Interfaces\Informational interfaces
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     *  The checking for required interfaces will be inside the method
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Post-Processors Collection and FALSE otherwise
     *
     * @throws \Next\File\Upload\UploadException
     *  Given Post-Processor is not acceptable in the Post-Processors Chain
     */
    public function accept( Object $object ) {

        if( ! $object instanceof PostProcessor && ! $object instanceof Informational ) {
            throw UploadExceptionException::invalidChainPostProcessor( $object );
        }

        return TRUE;
    }
}
