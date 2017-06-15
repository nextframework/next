<?php

/**
 * Components Invoker Class | Components\Invoker.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components;

/**
 * Wraps two \Next\Components\Object, a Caller Object and a Callee Object,
 * encapsulating them in a common interface as part of Extended Context Concept
 *
 * @package    Next\Components
 */
class Invoker {

    /**
     * Caller Object
     *
     * @var \Next\Components\Object $caller
     */
    private $caller;

    /**
     * Callee Object
     *
     * @var \Next\Components\Object $callee
     */
    private $callee;

    /**
     * Invoker Constructor
     *
     * @param \Next\Components\Object $caller
     *  Caller Object
     *
     * @param \Next\Components\Object $callee
     *  Callee Object
     */
    function __construct ( Object $caller, Object $callee ) {

        $this -> caller =& $caller;
        $this -> callee =& $callee;
    }

    /**
     * Get Caller Object
     *
     * @return \Next\Components\Object
     *  Caller Object
     */
    public function getCaller() {
        return $this -> caller;
    }

    /**
     * Get Callee Object
     *
     * @return \Next\Components\Object
     *  Callee Object
     */
    public function getCallee() {
        return $this -> callee;
    }
}