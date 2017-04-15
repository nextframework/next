<?php

namespace Next\Controller\Dispatcher;

use Next\Components\Object;    # Object Class

/**
 * Controller Dispatcher Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
     * @return Next\Controller\Dispatcher\Dispatcher
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
     * @return Next\Controller\Dispatcher\Dispatcher
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
