<?php

/**
 * Validator Chain Class | Validate\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate;

use Next\Components\Interfaces\Informational;          # Informational Interface

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

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
     * @return boolean|\Next\Validate\Validator
     *  TRUE if there's no Validator added to the Chain -OR- if the
     *  value is valid by all Validators on it and
     *  \Next\Validate\Validator Object otherwise
     */
    public function validate() {

        if( count( $this ) == 0 ) return TRUE;

        foreach( $this -> getIterator() as $validator ) {

            $results = $validator -> validate();

            // Grouping any Informational Messages sent by each Validator

            $this -> _info[ (string) $validator ] = $validator -> getInformationalMessage();

            if( $results === FALSE ) return $validator;
        }

        return TRUE;
    }

    // Abstract Method Implementation

    /**
     * Check Object acceptance
     *
     * Check if given Validator is acceptable in Validator Chain
     * To be valid, the Validator must implement both \Next\Validate\Validator
     * and \Next\Components\Interfaces\Informational interfaces
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     *  The checking for required interfaces will be inside the method
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Validators Collection and FALSE otherwise
     *
     * @throws \Next\Validate\ValidateException
     *  Given Validator is not acceptable in the Validator Chain
     */
    public function accept( Object $object ) {

        if( ! $object instanceof Validator || ! $object instanceof Informational ) {
            throw ValidateException::invalidChainValidator( $object );
        }

        return TRUE;
    }
}
