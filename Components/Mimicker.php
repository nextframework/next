<?php

/**
 * Extended Context Component Mimicker Class | Components\Mimicker.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validation\Verifiable;    # Verifiable Interface
use Next\Components\Object;        # Object Class

/**
 * The Mimicker Object literally tries to make a regular object mimic
 * an Object and thus be also accepted in a Extended Context
 *
 * @package    Next\Components
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Validation\Verifiable
 *             Next\Components\Object
 */
class Mimicker extends Object implements Verifiable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        // The object to be mimicked as an instance of Next\Components\Object

        'resource' => [ 'required' => TRUE ],
    ];

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @return \Next\Exception\Exceptions\InvalidArgumentException
     *  Resource not mimic-able
     */
    public function verify() : void {

        if( ! is_object( $this -> options -> resource ) ) {

            throw new InvalidArgumentException(
                'Only objects can (or need to) be mimic-able'
            );
        }
    }

    // Accessors

    /**
     * Get mimicked Object
     *
     * @return object
     *  Mimicked Object
     */
    public function getMimicked() {
        return $this -> options -> resource;
    }
}