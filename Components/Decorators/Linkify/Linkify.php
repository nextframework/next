<?php

namespace Next\Components\Decorators\Linkify;

interface Linkify {

    /**
     * Namespaced Class/Function RGEXP
     *
     * @var string
     */
    const REGEXP = '(?:
                        \\\\?(?<namespace>(\\\\?\w+\\\\)+
                        \w+)
                        (?<sro>(::)?)
                        (?:(?<method>\w+)\(\))?
                    |
                        (?<function>\w+)\(\)?
                    )';
}