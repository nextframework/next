<?php

/**
 * Routes Generators Applications' Annotations Class | Tools\Routes\Generators\Annotations\Applications.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\Routes\Generators\Annotations;

use Next\Application\Application as ApplicationInterface;    # Applications Interface
use Next\Components\Object;                                  # Object Class

/**
 * Defines the Applications Analyzer, filtering through data
 * reflected and preparing structure for the Routes Generator process
 *
 * @package    Next\Tools\Routes\Generators
 */
class Applications implements Annotations {

    /**
     * Domain Prefix
     *
     * @var string
     */
    const DOMAIN_PREFIX    =    '!Domain';

    /**
     * Path Prefix
     *
     * @var string
     */
    const PATH_PREFIX      =    '!Path';

    /**
     * Application Object
     *
     * @var \Next\Application\Application $application
     */
    private $application;

    /**
     * Applications Annotations Constructor
     *
     * @param \Next\Application\Application $application
     *  Application to get Annotations from
     */
    public function __construct( ApplicationInterface $application ) {

        $this -> application =& $application;
    }

    // Annotations Interface Method Implementation

    /**
     * Get Annotations Found
     *
     * @return array
     *  Found Annotations
     */
    public function getAnnotations() {

        $data = new \ArrayIterator;

        // Finding Domain Modifier

        $domain = $this -> matchDomainAnnotations( $this -> application );

        $data -> offsetSet( 'domain', ( count( $domain ) > 0 ? $domain : NULL ) );

        // Finding Path Modifier

        $path = $this -> matchPathAnnotation( $this -> application );

        $data -> offsetSet( 'path', ( count( $path ) > 0 ? $path : NULL ) );

        // Listing Controllers Methods

        $actions = $this -> application -> getControllers() -> getIterator();

        iterator_apply(

            $actions,

            function( \Iterator $iterator ) use( &$data ) {

                // Thankfully we can reuse the variable name because of different scope  ^_^

                $actions = new Actions( $iterator -> current() -> getClass() );

                if( $actions -> current() instanceof \ReflectionMethod ) {

                    $data -> offsetSet(

                        $actions -> current() -> class,

                        $actions -> getAnnotations()
                    );
                }

                return TRUE;
            },

            array( $actions )
        );

        return $data;
    }

    // Auxiliary Methods

    /**
     * Match Domain Annotations
     *
     * Domain Annotations start with !Domain
     *
     * @param \Next\Components\Object $application
     *  Application Object
     *
     * @return array
     *  Found Annotations
     */
    private function matchDomainAnnotations( Object $application ) {

        $domains = preg_grep(

            sprintf( '/%s/', self::DOMAIN_PREFIX ),

            preg_split('/[\n\r]+/', $application -> getClass() -> getDocComment() )
        );

        $domains = preg_replace( sprintf( '/.*?%s\s*/', self::DOMAIN_PREFIX ), '', $domains );

        return array_shift( $domains );
    }

    /**
     * Match Path Annotation
     *
     * Path Annotations start with !Path
     *
     * @param \Next\Components\Object $application
     *  Application Object
     *
     * @return string
     *  Found Annotation
     */
    private function matchPathAnnotation( Object $application ) {

        $path = preg_grep(

            sprintf( '/%s/', self::PATH_PREFIX ),

            preg_split('/[\n\r]+/', $application -> getClass() -> getDocComment() )
        );

        $path = preg_replace( sprintf( '/.*?%s\s*/', self::PATH_PREFIX ), '', $path );

        return array_shift( $path );
    }
}
