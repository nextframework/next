<?php

namespace Next\Components\Observer;

/**
 * Subject Interface
 *
 * Part of Observer Design Pattern
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Subject {

    /**
     * Attach a new Observer to this Subject
     *
     * @param Next\Components\Interfaces\Observer $observer
     *  Observer to be attached to Subject
     */
    public function attach( Observer $observer );

    /**
     * Detach an Observer from this Subject
     *
     * @param Next\Components\Interfaces\Observer $observer
     *  Observer to be detached from Subject
     */
    public function detach( Observer $observer );

    /**
     * Notify all attached Observers about Subject changes
     */
    public function notify();
}
