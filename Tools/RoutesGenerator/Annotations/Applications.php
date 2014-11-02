<?php

namespace Next\Tools\RoutesGenerator\Annotations;

use Next\Application\Application as ApplicationInterface;    # Applications Interface
use Next\Components\Object;                                  # Object Class

/**
 * Routes Generator: Application Annotations Analyzer
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
     * @var Next\Application\Application $application
     */
    private $application;

    /**
     * Applications Annotations Constructor
     *
     * @param Next\Application\Application $application
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

        /*$domains = $this -> matchDomainAnnotations( $this -> application );

        if( count( $domains ) > 0 ) {

            $data -> offsetSet( 'Domains', $domains );
        }*/

        // Finding Path Modifier

        $path = $this -> matchPathAnnotation( $this -> application );

        $data -> offsetSet( 'path', ( count( $path ) > 0 ? $path : NULL ) );

        // Listing Controllers Methods

        $actions = $this -> application -> getControllers() -> getIterator();

        iterator_apply(

            $actions,

            function( \Iterator $iterator ) use( &$data ) {

                $action = new Actions(

                    new \ArrayIterator(
                        $iterator -> current() -> getClass() -> getMethods()
                    )
                );

                $data -> offsetSet(

                    $action -> current() -> class,

                    $action -> getAnnotations()
                );

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
     * @param Next\Components\Object $application
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

        return preg_replace( sprintf( '/.*?%s\s*/', self::DOMAIN_PREFIX ), '', $domains );
    }

    /**
     * Match Path Annotation
     *
     * Path Annotations start with !Path
     *
     * @param Next\Components\Object $application
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
