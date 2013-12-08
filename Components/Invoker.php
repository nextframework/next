<?php

namespace Next\Components;

/**
 * Invoker Class
 *
 * Invoker intermediates calling processes involving a caller and a callee
 * Objects by encapsulating them in a common interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Invoker {

    /**
     * Caller Object
     *
     * @var Next\Components\Object $caller
     */
    private $caller;

    /**
     * Callee Object
     *
     * @var Next\Components\Object $callee
     */
    private $callee;

    /**
     * Invoker Constructor
     *
     * @param Next\Components\Object $caller
     *   Caller Object
     *
     * @param Next\Components\Object $callee
     *   Callee Object
     */
    function __construct ( Object $caller, Object $callee ) {

        $this -> caller =& $caller;

        $this -> callee =& $callee;
    }

    /**
     * Get Caller Object
     *
     * @return Next\Components\Object
     *   Caller Object
     */
    public function getCaller() {
        return $this -> caller;
    }

    /**
     * Get Callee Object
     *
     * @return Next\Components\Object
     *   Callee Object
     */
    public function getCallee() {
        return $this -> callee;
    }
}