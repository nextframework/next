<?php

namespace Next\Validate;

use Next\Components\Interfaces\Informational;      # Informational Interface

use Next\Components\Object;                         # Object Class
use Next\Components\Iterator\AbstractCollection;    # Abstract Collection Class

/**
 * Validator Chain
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Chain extends AbstractCollection {

    /**
     * Executes all Validators added to the Chain
     *
     * @param  mixed $data
     *  Data to validate
     *
     * @return boolean|Next\Validate\Validate
     *  TRUE if valid by all Validators and Validator Object otherwise
     */
    public function validate( $data ) {

        if( count( $this ) == 0 ) return TRUE;

        foreach( $this -> getIterator() as $validator ) {

            if( ! $validator -> validate( $data ) ) return $validator;
        }

        return TRUE;
    }

    // Abstract Method Implementation

    /**
     * Check Object acceptance
     *
     * Check if given Validator is acceptable in Validator Chain
     * To be valid, the Validator must implement both Next\Validate\Validate
     * and Next\Components\Interfaces\Informational interfaces
     *
     * @param Next\Components\Object $object
     *  An Object object
     *
     *  The checking for required interfaces will be inside the method
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Validators Collection and FALSE otherwise
     *
     * @throws Next\Validate\ValidateException
     *  Given Validator is not acceptable in the Validator Chain
     */
    public function accept( Object $object ) {

        if( ! $object instanceof Validate || ! $object instanceof Informational ) {
            throw ValidateException::invalidChainValidator( $object );
        }

        return TRUE;
    }
}
