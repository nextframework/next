<?php

namespace Next\Components\Interfaces;

use Next\Application\Application;    # Application Interface

interface Plugin {

    /**
     * Routines to be performed after any possible post-processing, but
     * before the Plugin can be activated. E.g: Before it's added in a database
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onBeforeActivate( Application $application );

    /**
     * Routines to be performed after the Plugin can be activated.
     * E.g: After it's added in a database
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onAfterActivate( Application $application );

    /**
     * Routines to be performed before the Plugin can be unregistered.
     * E.g: Before it's removed from a database
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onBeforeDeactivate( Application $application );

    /**
     * Routines to be performed after the Plugin can be unregistered.
     * E.g: After it's removed from a database
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onAfterDeactivate( Application $application );

    /**
     * Routines to be performed before the Plugin can be modified.
     * E.g: Before its data is updated in a database
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onBeforeModify( Application $application );

    /**
     * Routines to be performed after the Plugin can be modified.
     * E.g: After its data is updated in a database
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onAfterModify( Application $application );

    /**
     * Routines to be performed before the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could delete
     * backup files that are too old
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onBeforeRun( Application $application );

    /**
     * The main routine, with all the logics of the Plugin,
     * preferably without any direct output
     *
     * @param  Next\Application\Application $application
     *  Application Object
     */
    public function run( Application $application );

    /**
     * Routines to be performed after the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could send
     * the currently generated file as email attachment to the System Administrator
     *
     * @param Next\Application\Application $application
     *  Application Object
     */
    public function onAfterRun( Application $application );
}