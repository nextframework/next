<?php

namespace Next\Components\Interfaces;

interface Plugin {

    /**
     * Routines to be performed after any possible post-processing, but
     * before the Plugin can be activated. E.g: Before it's added in a database
     */
    public function onBeforeActivate();

    /**
     * Routines to be performed after the Plugin can be activated.
     * E.g: After it's added in a database
     */
    public function onAfterActivate();

    /**
     * Routines to be performed before the Plugin can be unregistered.
     * E.g: Before it's removed from a database
     */
    public function onBeforeDeactivate();

    /**
     * Routines to be performed after the Plugin can be unregistered.
     * E.g: After it's removed from a database
     */
    public function onAfterDeactivate();

    /**
     * Routines to be performed before the Plugin can be modified.
     * E.g: Before its data is updated in a database
     */
    public function onBeforeModify();

    /**
     * Routines to be performed after the Plugin can be modified.
     * E.g: After its data is updated in a database
     */
    public function onAfterModify();

    /**
     * Routines to be performed before the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could delete
     * backup files that are too old
     */
    public function onBeforeRun();

    /**
     * Routines to be performed after the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could send
     * the currently generated file as email attachment to the System Administrator
     */
    public function onAfterRun();
}