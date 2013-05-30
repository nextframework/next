<?php

namespace Next\DB\Query\Renderer;

use Next\Components\Object;    # Object Class

/**
 * Query Renderer Abstract Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractRenderer extends Object implements Renderer {

    /**
     * Quote Identifier Symbol
     *
     * @var string $quoteIdentifier
     */
    protected $quoteIdentifier;

    /**
     * Renderer Constructor
     *
     * @param string $quoteIdentifier
     *   Quote Identifier
     */
    public function __construct( $quoteIdentifier ) {

        $this -> quoteIdentifier =& $quoteIdentifier;
    }

    /**
     * Get Quote Identifier
     *
     * @return string
     *   The Quote Identifier
     */
    public function getQuoteIdentifier() {
        return $this -> quoteIdentifier;
    }
}
