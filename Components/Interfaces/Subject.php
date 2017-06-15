<?php

/**
 * Subject/Observer Component Subject Interface | Components\Interfaces\Subject.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Interfaces;

/**
 * Subject Interface, part of Observer Design Pattern.
 * A Subjects notifies their Observers that something happened so
 * they can do something on their own
 *
 * @package    Next\Components\Interfaces
 */
interface Subject {

    /**
     * Attach a new Observer to this Subject
     *
     * @param \Next\Components\Interfaces\Observer $observer
     *  Observer to be attached to Subject
     */
    public function attach( Observer $observer );

    /**
     * Detach an Observer from this Subject
     *
     * @param \Next\Components\Interfaces\Observer $observer
     *  Observer to be detached from Subject
     */
    public function detach( Observer $observer );

    /**
     * Notify all attached Observers about Subject changes
     */
    public function notify();
}
