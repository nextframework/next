<?php

/**
 * Controller Dispatcher Abstract Class | Controller\Dispatcher\AbstractDispatcher.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Controller\Dispatcher;

use Next\Components\Object;    # Object Class

/**
 * Defines the base structure for a Controller Dispatcher
 *
 * @package    Next\Controller\Dispatcher
 */
abstract class AbstractDispatcher extends Object implements Dispatcher {

    /**
     * Flag to condition whether or not the Response Object
     * will be returned or not
     *
     * @var boolean $shouldReturn
     */
    private $shouldReturn = FALSE;

    /**
     * Dispatching Control Flag
     *
     * @var boolean $isDispatched
     */
    protected $isDispatched = FALSE;

    /**
     * Set Response as Dispatched
     *
     * @param boolean $flag
     *  Defines whether or not the Controller was already dispatched
     *
     * @return \Next\Controller\Dispatcher\Dispatcher
     *  Dispatcher Instance (Fluent Interface)
     */
    public function setDispatched( $flag ) {

        $this -> isDispatched = (bool) $flag;

        return $this;
    }

    /**
     * Checks if a Controller was Dispatched
     *
     * @return boolean
     *  TRUE if a Controller was already Dispatched and FALSE otherwise
     */
    public function isDispatched() {
        return $this -> isDispatched;
    }

    /**
     * Change state of dispatching return conditional flag
     *
     * @param boolean $flag
     *  New state for the flag
     *
     * @return \Next\Controller\Dispatcher\Dispatcher
     *  Dispatcher Instance (Fluent Interface)
     */
    public function returnResponse( $flag ) {

        $this -> shouldReturn = (bool) $flag;

        return $this;
    }

    /**
     * Get current state of dispatching returning conditional flag
     *
     * @return boolean
     *  Dispatching returning flag value
     */
    public function shouldReturn() {
        return $this -> shouldReturn;
    }
}
