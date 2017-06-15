<?php

/**
 * Subject/Observer Component Observer Interface | Components\Interfaces\Observer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Interfaces;

/**
 * Observer Interface, part of Observer Design Pattern.
 * Observers are attached to a \Next\Components\Interfaces\Subject and
 * execute a routine whenever they're notified of a state change
 *
 * @package    Next\Components\Interfaces
 */
interface Observer {

    /**
     * Receives update from Subject
     *
     * @param \Next\Components\Interfaces\Subject $subject
     *  The Subject notifying the observer of an update
     */
    public function update( Subject $subject );
}
