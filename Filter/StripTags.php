<?php

/**
 * Strip Tags Filter Class | Filter/StripTags.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Filter;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Configurable;    # Configurable Interface
use Next\Components\Object;                     # Object Class

/**
 * Strips out (X)HTML tags of input string.
 *
 * Original implementation by "Sherif" (https://stackoverflow.com/a/39469240/5613506)
 *
 * @package    Next\Filter
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Interfaces\Configurable
 *             Next\Components\Object,
 *             Next\Filter\Filterable
 *             DOMDocument
 *             DOMNode
 */
class StripTags extends Object implements Filterable, Configurable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        /**
         * @internal
         *
         * Data to filter
         */
        'data'     => [ 'required' => TRUE, 'default' => NULL ],

        'allowedTags' => [

            'required' => FALSE,

            'default' => [

                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'pre', 'code', 'blockquote', 'cite', 'q',
                'strong', 'em', 'del', 'kbd',
                'img', 'p', 'a',
                'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
                'ul', 'ol', 'li',
            ]
        ],

        'allowedAttributes' => [

            'required' => FALSE,

            'default' => [

                'a'   => [ 'href' ],
                'img' => [ 'src' ],
                'pre' => [ 'class' ]
            ]
        ],

        'forcedAttributes' => [

            'required' => FALSE,

            'default' => [
                'a' => ['target' => '_blank'],
            ]
        ],

        'version'  => [ 'required' => FALSE, 'default' => '1.0' ],

        'encoding' => [ 'required' => FALSE, 'default' => 'UTF-8' ],
    ];

    /**
     * DOMDocument Object
     *
     * @var \DOMDocument $DOM
     */
    protected $DOM;

    /**
     * Post-Initialization Configuration.
     * Creates a DOMDocument Object, loading input data as HTML
     */
    public function configure() : void {

        $this -> DOM = new \DOMDocument(
            $this -> options -> version,
            $this -> options -> encoding
        );

        $this -> DOM -> loadHTML(

            $this -> options -> data,

            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
    }

    // Filterable Interface Method Implementation

    /**
     * Filters input data
     *
     * @return string
     *  Input data with (X)HTML Tags and/or attributes stripped.
     *
     * @see \Next\Filter\Blacklist
     *  Detailed explanation on why the Exception is thrown
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'data' has no value (see above)
     */
    public function filter() : string {

        if( $this -> options -> data === NULL ) {
            throw new InvalidArgumentException( 'Nothing to filter' );
        }

        $this -> configure();

        $this -> stripTags( $this -> DOM );

        return $this -> DOM -> saveHTML( $this -> DOM );
    }

    // Auxiliary Methods

    /**
     * Strips out all but the allowed (X)HTML Tags of all \DOMNodes found
     *
     * @param  \DOMNode $node
     *  DOMNode node to strip out (X)HTML tags and attributes as well
     *  as enforce attributes
     */
    protected function stripTags( \DOMNode $node ) : void {

        $change = $remove = [];

        foreach( $this -> walk( $node ) as $n ) {

            if( $n instanceof \DOMText || $n instanceof \DOMDocument ) continue;

            $this -> stripAttributes( $n );

            $this -> forceAttributes( $n );

            if( ! in_array( $n -> nodeName, $this -> options -> allowedTags, TRUE ) ) {

                $remove[] = $n;

                foreach( $n -> childNodes as $child ) {
                    $change[] = [ $child, $n ];
                }
            }
        }

        foreach( $change as list( $a, $b ) ) {
            $b -> parentNode -> insertBefore( $a, $b );
        }

        foreach( $remove as $a ) {

            if( $a -> parentNode ) {
                $a -> parentNode -> removeChild( $a );
            }
        }
    }

    /**
     * Strips out all but the allowed (X)HTML Attributes of
     * given \DOMNode node
     *
     * @param  \DOMNode $node
     *  DOMNode node to from which (X)HTML Attributes will be stripped
     */
    protected function stripAttributes( \DOMNode $node ) : void {

        for( $i = $node -> attributes -> length - 1; $i >= 0; $i-- ) {

            $attribute = $node -> attributes->item( $i );

            if( ! isset( $this -> options -> allowedAttributes -> {$node -> nodeName} ) ||
                    ! in_array( $attribute -> name, $this -> options -> allowedAttributes -> {$node -> nodeName}, TRUE ) ) {

                $node -> removeAttributeNode( $attribute );
            }
        }
    }

    /**
     * Enforces (X)HTML Attributes to given \DOMNode node
     *
     * E.g: Adds `target="_blank"` to all `<a>` tags, so all of
     * them will open in a new window/tab
     *
     * @param  \DOMNode $node
     *  DOMNode node in which forced (X)HTML Attributes will be added
     */
    protected function forceAttributes( \DOMNode $node ) : void {

        if( isset( $this -> options -> forcedAttributes -> {$node -> nodeName} ) ) {

            foreach( $this -> options -> forcedAttributes -> {$node -> nodeName} as $attribute => $value ) {
                $node -> setAttribute( $attribute, $value );
            }
        }
    }

    /**
     * Traverses through all DOMNodes within given DOMNode recursively
     *
     * @param  \DOMNode $node
     *  DOMNode node to traverse
     */
    protected function walk( \DOMNode $node, $skipParent = false ) : \Generator {

        if( ! $skipParent ) yield $node;

        if( $node -> hasChildNodes() ) {

            foreach( $node -> childNodes as $n ) {
                yield from $this -> walk( $n );
            }
        }
    }
}