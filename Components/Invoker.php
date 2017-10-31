<?php

/**
 * Components Invoker Class | Components\Invoker.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

/**
 * Wraps two Objects, a Caller and a Callee, encapsulating them in a common
 * interface as part of Extended Context Concept
 *
 * @package    Next\Components
 *
 * @uses       Next\Components\Object
 */
class Invoker extends Object {

    /**
     * Caller Object
     *
     * @var \Next\Components\Object $caller
     */
    protected $caller;

    /**
     * Callee Object
     *
     * @var \Next\Components\Object $callee
     */
    protected $callee;

    /**
     * Invoker Constructor
     *
     * @param \Next\Components\Object $caller
     *  Caller Object
     *
     * @param \Next\Components\Object $callee
     *  Callee Object
     */
    public function __construct( Object $caller, Object $callee ) {

        $this -> caller = $caller;
        $this -> callee = $callee;
    }

    /**
     * Get Caller Object
     *
     * @return \Next\Components\Object
     *  Caller Object
     */
    public function getCaller() : Object {
        return $this -> caller;
    }

    /**
     * Get Callee Object
     *
     * @return \Next\Components\Object
     *  Callee Object
     */
    public function getCallee() : Object {
        return $this -> callee;
    }
}