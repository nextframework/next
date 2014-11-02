<?php

namespace Next\XML;

use Next\Components\Interfaces\Parameterizable;           # Parameterizable Interface
use Next\HTTP\Response\ResponseException;                 # Response Exception Class
use Next\Components\Parameter;                            # Parameter Class
use Next\HTTP\Response;                                   # Response Class
use Next\HTTP\Headers\Fields\Entity\ContentType;          # Content-Type Entity Header

/**
 * XML Writer Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Writer extends \XMLWriter implements Parameterizable {

    /**
     * XML Writer Default Options
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array(

        'addPrologue' => TRUE,
        'version'     => '1.0',
        'charset'     => 'utf-8',

        'indent'      => array(

            'enabled'     => TRUE,
            'char'        => "\t",
            'repeat'      => 1
        ),
    );

    /**
     * XML Writer Options
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * XML Writer Constructor
     *
     * @param mixed|optional $options
     *
     *   <br />
     *
     *   <p>List of Options to affect XML Output. Acceptable values are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>Associative and multidimensional array</li>
     *
     *           <li>
     *
     *               An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *           </li>
     *
     *           <li>A well formed Parameter Object</li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>The arguments taken in consideration are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>
     *
     *               <p><strong>addPrologue</strong></p>
     *
     *               <p>
     *                   Defines whether or not the XML Prologue
     *                   <em>(<?xml version="1.0"...)</em> will be added.
     *               </p>
     *
     *               <p>Default Value: <strong>TRUE</strong>.</p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p><strong>version</strong>
     *
     *               <p>
     *                   The XML version, only visible in output if
     *                   <strong>addPrologue</strong> option is set to TRUE.
     *               </p>
     *
     *               <p>
     *                   Default Value: <strong>1.0</strong>
     *                   (as string, not double)
     *               </p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p><strong>charset</strong></p>
     *
     *               <p>
     *                   The XML Character Set to be used.
     *               </p>
     *
     *               <p>
     *                   Only visible if <strong>addPrologue</strong>
     *                   is set as TRUE.
     *               </p>
     *
     *               <p>Default Value: <strong>utf-8</strong></p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p><strong>indent</strong></p>
     *
     *               <p>
     *                   A subgroup of options containing one or more of
     *                   the following options:
     *               </p>
     *
     *               <ul>
     *
     *                   <li>
     *
     *                       <p><strong>enabled</strong></p>
     *
     *                       <p>
     *                           Defines if output XML will be indented
     *                           or not.
     *                       </p>
     *
     *                       <p>Default Value: <strong>TRUE</strong></p>
     *
     *                   </li>
     *
     *                   <li>
     *
     *                       <p><strong>char</strong></p>
     *
     *                       <p>The character used for indentation</p>
     *
     *                       <p>
     *                           Usually a <strong>\t</strong> (tabulation) or
     *                           a blank space is used.
     *                       </p>
     *
     *                       <p>
     *                           Default Value: <strong>\t</strong>
     *                           (tabulation)
     *                       </p>
     *
     *                   </li>
     *
     *                   <li>
     *
     *                       <p><strong>repeat</strong></p>
     *
     *                       <p>
     *                           How many times character defined in
     *                           <strong>char</strong> option will be
     *                           repeated.
     *                       </p>
     *
     *                       <p>
     *                           Default Value: <strong>1</strong>
     *                           (one tabulation)
     *                       </p>
     *
     *                   </li>
     *
     *               </ul>
     *
     *           </li>
     *
     *       </ul>
     *
     *   </p>
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        // Setting Up Options Object

        $this -> options = new Parameter( $this -> defaultOptions, $options );

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
     * @return Next\XML\Writer
     *  XML Writer Object (Fluent Interface)
     */
    public function addParent( $name, array $attributes = array() ) {

        // Starting Parent Node. This node is closed automatically

        $this -> startElement( $name );

        // Writing Roo Node Attributes

        $this -> writeAttributes( $attributes );

        return $this;
    }

    /**
     * Add a new child node in XML
     *
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
     * @return Next\XML\Writer
     *  XML Writer Object (Fluent Interface)
     */
    public function addChild( $name, $value = NULL, array $attributes = array(), $close = FALSE ) {

        // Creating Node

        $this -> startElement( $name );

        // Writing its Attributes (if any)

        if( count( $attributes ) > 0 ) {

            $this -> writeAttributes( $attributes );
        }

        // Adding its value...

        $this -> text( $value );

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
     * @param Next\HTTP\Response|optional $response
     *
     *   <p>Response Object</p>
     *
     *   <p>If set, a Content-Type Header will also be sent</p>
     *
     * @param boolean|optional $decode
     *  When returning, defines whether or not the entities will be decoded
     *
     * @return string|void
     *
     *   <p>
     *      If a Response Object is not provided
     *      {@link http://www.php.net/XMLWriter XmlWriter} Memory
     *      will be returned
     *   </p>
     *
     *   <p>
     *       Otherwise, the Memory will be sent, with or without,
     *       the proper Header
     *   </p>
     */
    public function output( Response $response = NULL, $decode = TRUE ) {

        // Ending XML Document (root node)

        $this -> endDocument();

        if( ! is_null( $response ) ) {

            // Trying to send the header

            try {

                $response -> addHeader( new ContentType( 'text/xml' ) ) -> send();

            } catch( ResponseException $e ) {

                /**
                 * @internal
                 * Unable to send the header? Exception thrown?
                 * Well, only the XML will be displayed instead be "styled"
                 */
            }

            print $this -> outputMemory(); exit;

        } else {

            $output = $this -> outputMemory();

            if( ! $this -> options -> indent -> enabled ) {

                $output = str_replace( "\n", '', $output );
            }

            if( $decode ) {

                return html_entity_decode( $output );
            }

            return $output;
        }
    }

    // Parabeterizable Interface Methods Implementation

    /**
     * Set Up XML Writer Options
     *
     * Not used, but overwritable
     */
    public function setOptions() {}

    /**
     * Get XML Writer Options
     *
     * @return Next\Components\Parameter
     *  Parameter Object with merged options
     */
    public function getOptions() {
        return $this -> options;
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
    private function writeAttributes( array $attributes ) {

        foreach( $attributes as $entry => $value ) {

            $this -> writeAttribute( (string) $entry, $value );
        }
    }
}