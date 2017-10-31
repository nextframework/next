<?php

/**
 * Linkify Interface | Components\Decorators\Linkify\Linkify.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Decorators\Linkify;

/**
 * Linkify Objects decorates resources by adding links to predefined
 * keywords or expressions
 *
 * @package    Next\Components\Decorators
 */
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