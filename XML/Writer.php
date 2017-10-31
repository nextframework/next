<?php

/**
 * XML Writer Class | XML\Writer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\XML;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\RuntimeException;

use Next\Components\Object;                  # Object Class
use Next\Components\Invoker;                 # Invoker Class
use Next\Components\Mimicker;                # Object Mimicker Class

use Next\HTTP\Response;                      # Response Class
use Next\HTTP\Headers\Entity\ContentType;    # Content-Type Entity Header

/**
 * The XML Writer simplifies the creation of XML Nodes through the native
 * XmlWriter Class but also offers, through the Extended Context, the
 * possibility of manually invoking other methods without need of inheritance
 *
 * @package    Next\XML
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Exception\Exceptions\RuntimeException;
 *             Next\Components\Object
 *             Next\Components\Invoker
 *             Next\Components\Mimicker
 *             Next\HTTP\Response
 *             Next\HTTP\Headers\Entity\ContentType
 *             XmlWriter
 */
class Writer extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'addPrologue' => TRUE,
        'version'     => '1.0',
        'charset'     => 'utf-8',

        'indent'      => [

            'enabled'     => TRUE,
            'char'        => "\t",
            'repeat'      => 1
        ],
    ];

    /**
     * Additional initialization.
     * Extends XML\Writer Context to native XmlWriter Class and
     * starts the XML document
     */
    protected function init() : void {

        $this -> extend(
            new Invoker( $this, new Mimicker( [ 'resource' => new \XmlWriter ] ) )
        );

        // Starting XML Document

        $this -> openMemory();

        // Should we indent XML File?

        if( $this -> options -> indent -> enabled ) {

            $this -> setIndent( TRUE );

            $this -> setIndentString(
                str_repeat( $this -> options -> indent -> char, $this -> options -> indent -> repeat )
            );
        }

        // Should we add XML Prologue (<?xml version="1.0"...)

        if( $this -> options -> addPrologue ) {
            $this -> startDocument( $this -> options -> version, $this -> options -> charset );
        }
    }

    /**
     * Add a parent node in XML
     *
     * Add a parent node in XML
     *
     * @param string $name
     *  Parent Node Name
     *
     * @param array|optional $attributes
     *  Parent Node Attributes
     *
     * @return \Next\XML\Writer
     *  XML Writer Object (Fluent Interface)
     */
    public function addParent( $name, array $attributes = [] ) : Writer {

        // Starting Parent Node. This node is closed automatically

        $this -> startElement( $name );

        // Writing Roo Node Attributes

        $this -> writeAttributes( $attributes );

        return $this;
    }

    /**
     * Add a new child node in XML
     *
     * @param string $name
     *  Child Node Name
     *
     * @param string|optional $value
     *  Optional Child Node Value
     *
     * @param array|optional $attributes
     *  Optional Child Node Attributes
     *
     * @param boolean|optional $close
     *  If TRUE closes the first parent node of the child
     *
     * @param boolean|optional $addCDATABlock
     *  Defines whether or not the Node value will be written as plain
     *  text or wrapped in a CDATA Block
     *
     * @return \Next\XML\Writer
     *  XML Writer Object (Fluent Interface)
     *
     * @link http://wikipedia.org/wiki/CDATA
     */
    public function addChild( $name, $value = NULL, array $attributes = [], $close = FALSE, $addCDATABlock = FALSE ) : Writer {

        // Creating Node

        $this -> startElement( $name );

        // Writing its Attributes (if any)

        if( count( $attributes ) > 0 ) {

            $this -> writeAttributes( $attributes );
        }

        // Adding its value...

        if( $addCDATABlock !== FALSE ) {
            $this -> writeCdata( $value );
        } else {
            $this -> text( $value );
        }

        // ... and finishing it

        $this -> endElement();

        // Should we close the parent node?

        if( $close ) { $this -> endElement(); }

        return $this;
    }

    /**
     * Prints/Returns the XML Output Memory
     *
     * Prints/Returns the XML Output Memory
     *
     * If a Response Object is set, a 'Content-type' header will be sent
     * and the output printed.
     *
     * Otherwise the output will be returned 'as is'
     *
     * @param \Next\HTTP\Response|optional $response
     *  Response Object
     *  If set, a Content-Type Header will also be sent
     *
     * @param boolean|optional $decode
     *  When returning, defines whether or not the entities will be decoded
     *
     * @return \Next\HTTP\Response|string|void
     *  If a Response Object has not been provided then the
     *  [http://www.php.net/XMLWriter](XmlWriter) Memory Buffer will be
     *  returned as string
     *
     *  Otherwise the Memory Buffer will be flushed, with or without, the
     *  proper Header. However, if the response Object provided has been
     *  configured to be returned instead of flushed — through
     *  Response::shouldReturn() — nothing is returned
     */
    public function output( Response $response = NULL, $decode = TRUE ) {

        // Ending XML Document (root node)

        $this -> endDocument();

        $output = $this -> outputMemory();

        if( $response !== NULL ) {

            // Trying to send the header

            try {

                $response -> addHeader(
                    new ContentType( [ 'value' => 'text/xml' ] )
                );

                return $response -> appendBody( $output ) -> send();

            } catch( InvalidArgumentException | RuntimeException $e ) {

                /**
                 * @internal
                 *
                 * If an `Next\Exception\Exceptions\InvalidArgumentException`
                 * or a `Next\Exception\Exceptions\RuntimeException` is caught
                 * here the header couldn't be sent (unlikely) or, because
                 * headers have already been sent, the whole Response couldn't
                 * be sent
                 */
            }

        }

        if( ! $this -> options -> indent -> enabled ) {
            $output = strtr( $output, [ "\n" => '' ] );
        }

        return( $decode ? html_entity_decode( $output ) : $output );
    }

    // Auxiliary Methods

    /**
     * Write XML Attributes
     *
     * Iterate through given array and write XML Node Attributes.
     * This is a wrapper method used in addParent() and addChild()
     *
     * @param array $attributes
     *  XML Node Attributes
     */
    private function writeAttributes( array $attributes ) : void {

        foreach( $attributes as $entry => $value ) {
            $this -> writeAttribute( (string) $entry, $value );
        }
    }
}