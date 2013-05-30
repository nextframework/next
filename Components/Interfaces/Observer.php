<?php

namespace Next\Components\Observer;

/**
 * Observer Interface
 *
 * Part of Observer Design Pattern
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Observer {

    /**
     * Receive update from Subject
     *
     * @param Next\Components\Interfaces\Subject $subject
     *   The Subject notifying the observer of an update
     */
    public function update( Subject $subject );
}
