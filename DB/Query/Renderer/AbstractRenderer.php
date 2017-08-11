<?php

/**
 * Database Query Renderer Abstract Class | DB\Query\Renderer\AbstractRenderer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query\Renderer;

use Next\Components\Object;     # Object Class
use Next\File\Tools as File;    # File Tools

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
     *  Quote Identifier
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the Query Expression
     */
    public function __construct( $quoteIdentifier, $options = NULL ) {

        parent::__construct( $options );

        $this -> quoteIdentifier = $quoteIdentifier;
    }

    /**
     * Quote a string using the quote identifier defined
     *
     * @param string $string
     *  String to quote
     *
     * @return string
     *  Inout string, quote
     *
     * @see \Next\File\Tools::quote()
     */
    public function quote( $string ) {

        // Do we have a full database definition (e.g. db.table)?

        if( strpos( $string, '.' ) !== FALSE ) {

            $string = implode(
                sprintf( '%1$s.%1$s', $this -> quoteIdentifier ),
                explode( '.', $string )
            );
        }

        return File::quote( $string, $this -> quoteIdentifier );
    }

    /**
     * Get Quote Identifier
     *
     * @return string
     *  The Quote Identifier
     */
    public function getQuoteIdentifier() {
        return $this -> quoteIdentifier;
    }
}
