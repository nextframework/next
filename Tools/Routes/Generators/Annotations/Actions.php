<?php

namespace Next\Tools\Routes\Generators\Annotations;

use Next\Tools\Routes\Generators\GeneratorsException;    # Routes Generators Exception Class
use Next\Components\Utils\ArrayUtils;                    # Array Utils Class

/**
 * Routes Generator: Actions Annotations Analyzer
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Actions extends \FilterIterator implements Annotations {

    /**
     * Framework Token
     *
     * @var string
     */
    const FRAMEWORK       =    'Next';

    /**
     * Routes Token
     *
     * @var string
     */
    const ROUTE_PREFIX    =    '!Route';

    /**
     * Arguments Token
     *
     * @var string
     */
    const ARGS_PREFIX     =    '!Argument';

    /**
     * Controller Actions Annotations Constructor
     *
     * @param Iterator $iterator
     *  Actions Iterator
     *
     * @see https://bugs.php.net/bug.php?id=52560
     */
    public function __construct( \Iterator $iterator ) {

        parent::__construct( $iterator );

        $this -> rewind();
    }

    // Annotations Interface Method Implementation

    /**
     * Get Annotations Found
     *
     * @return array
     *  Found annotations
     *
     * @throws Next\Tools\Routes\Generators\GeneratorsException
     *  Route argument has less than 2 Components (a Name and a Type)
     */
    public function getAnnotations() {

        $data = array();

        $labels = array( 'name', 'type', 'acceptable', 'default', 'regex' );

        foreach( $this as $current ) {

            // Preparing Arguments Structure right now

            $args = array();

            foreach( $this -> findActionAnnotations( $current, self::ARGS_PREFIX ) as $index => $arg ) {

                $temp = explode( ',', $arg, 5 );

                // Basic Integrity Check

                if( count( $temp )  < 2 ) {

                    throw GeneratorsException::malformedArguments(

                        array( $current -> class, $current -> name )
                    );
                }

                // Equalizing argument definitions with structure labels

                ArrayUtils::equalize( $temp, $labels );

                // Cleaning and preparing argument data structure

                $temp = array_map(

                    function( $current ) {

                        $current = trim( $current );

                        return( empty( $current ) || $current === 'null' ? NULL : $current );
                    },

                    $temp
                );

                $args[] = array_combine( $labels, $temp) ;
            }

            // Saving Data

            $data[ $current -> name ] = array(

                'routes'    =>  $this -> findActionAnnotations( $current, self::ROUTE_PREFIX ),
                'args'      =>  $args
            );
        }

        return $data;
    }

    // FilterIterator Method Implementation

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * To be accepted, the method:
     *
     * <ul>
     *
     *     <li>Must have its visibility defined as PUBLIC</li>
     *
     *     <li>Must be declared as FINAL</li>
     *
     *     <li>
     *
     *         Should not be a Magic Method
     *         (__construct, __destruct, __call and son on)
     *
     *     </li>
     *
     *     <li>Must NOT have Framework Token in it name</li>
     *
     * </ul>
     *
     * @return boolean
     *  TRUE if annotation is acceptable as Action Annotation and FALSE otherwise
     */
    public function accept() {

        $method = $this -> getInnerIterator() -> current();

        return ( ( $method -> isPublic() && $method -> isFinal() ) &&
                 ( substr( $method -> name, 0, 2 ) !== '__' )      &&
                 ( strpos( $method -> class, self::FRAMEWORK ) === FALSE )
               );
    }

    // Auxiliary Methods

    /**
     * Find Actions Annotations
     *
     * @param ReflectionMethod $action
     *  Action to get Routes Annotations from
     *
     * @param string $annotationPrefix
     *  Annotation prefix used to distinguish an API doc-comment from an Action Annotation
     *
     * @return array
     *  Found Annotations
     */
    private function findActionAnnotations( \ReflectionMethod $action, $annotationPrefix ) {

        $annotation = preg_grep(

            sprintf( '/%s/', $annotationPrefix ),

            preg_split('/[\n\r]+/', $action -> getDocComment() )
        );

        return preg_replace(
            sprintf( '/.*?%s\s*/', $annotationPrefix ), '', $annotation
        );
    }
}
