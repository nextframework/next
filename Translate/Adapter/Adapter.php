<?php

namespace Next\Translate\Adapter;

use Next\Cache\Cacheable;       # Cacheable Interface
use Next\HTTP\Stream\Stream;    # HTTP Stream Interface

/**
 * Translate Adapters Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Adapter extends Cacheable {

    /**
     * Get Translation Table
     *
     * @param string|optional $key
     *   Cache Key
     */
    public function getTranslationTable( $key = NULL );

    /**
     * Set Translate Adapter Stream
     *
     * @param Next\HTTP\Stream\Stream $stream
     *   HTTP Stream from which data will be read
     */
    public function setStream( Stream $stream );
}
