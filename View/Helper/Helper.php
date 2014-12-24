<?php

namespace Next\View\Helper;

interface Helper {

    /**
     * Get the Helper name to be registered by View Engine
     */
    public function getHelperName();
}