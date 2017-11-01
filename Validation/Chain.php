<?php

/**
 * Validator Chain Class | Validation\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Informational;    # Informational Interface

use Next\Components\Object;                      # Object Class
use Next\Components\Collections\Collection;      # Abstract Collection Class

/**
 * A Collection for Validator Objects to be applied sequentially
 *
 * @package    Next\Validation
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Interfaces\Informational
 *             Next\Components\Object
 *             Next\Components\Collections\Collection
 *             Next\Validation\Validator
 */
class Chain extends Collection implements Validator {

    /**
     * Executes all Validators added to the Chain
     *
     * @return boolean|\Next\Validation\Validator
     *  TRUE if there's no Validator added to the Chain -OR- if the
     *  value is valid by all Validators on it and
     *  \Next\Validation\Validator Object otherwise
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
     * To be valid, the Validator must implement both Next\Validation\Validator
     * and Next\Components\Interfaces\Informational interfaces
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     *  The checking for required interfaces will be inside the method
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Validators Collection and FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Given Validator is not acceptable in the Validator Chain
     */
    public function accept( Object $object ) : bool {

        if( ! $object instanceof Validator || ! $object instanceof Informational ) {

            throw new InvalidArgumentException(

                sprintf(

                    '<strong>%s</strong> is not a valid Validator Chain

                    Validators must implement both <em>Next\Validation\Validator</em>
                    and <em>Next\Components\Interfaces\Informational</em> Interfaces',

                    $object
                )
            );
        }

        return TRUE;
    }
}
