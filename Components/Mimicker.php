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

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Components\Object;                   # Object Class

/**
 * The Mimicker Object literally tries to make a regular object mimic
 * a \Next\Components\Object and thus be also accepted in a Extended Context
 *
 * @package    Next\Components
 */
class Mimicker extends Object implements Verifiable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        // The object to be mimicked as an instance of \next\Components\Object

        'resource' => [ 'required' => TRUE ],
    ];

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Components\ContextException
     *  Thrown if provided resource to be mimicked is not an object
     */
    public function verify() {

        // Only objects can (or need to) be mimicked

        if( ! is_object( $this -> options -> resource ) ) {
            throw ContextException::notMimicable();
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