<?php

namespace Next\Tools\RoutesGenerator\Annotations;

use Next\Tools\RoutesGenerator\RoutesGeneratorException;

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
    const FRAMEWORK        =    'Next';

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
    const ARGS_PREFIX    =    '!Argument';

    /**
     * Controller Actions Annotations Constructor
     *
     * @param Iterator $iterator
     *   Actions Iterator
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
     *   Found annotations
     */
    public function getAnnotations() {

        $data = array();

        foreach( $this as $current ) {

            // Preparing Arguments Structure right now

            $args = array();

            $labels = array( 'name', 'type', 'acceptable', 'default', 'regex' );

            foreach( $this -> findActionAnnotations( $current, self::ARGS_PREFIX ) as $index => $arg ) {

                $temp = explode( ',', $arg, 5 );

                // Basic Integrity Check

                if( count( $temp )  < 2 ) {

                    throw RoutesGeneratorException::malformedArguments(

                        array( $current -> class, $current -> name )
                    );
                }

                // Equalizing argument definitions with structure labels

                \Next\Components\Utils\ArrayUtils::equalize( $temp, $labels );

                $args[] = array_combine( $labels, $temp );
            }

            // Saving Data

            $data[ $current -> name ] = array(

                'routes'    =>  $this -> findActionAnnotations(
                                    $current, self::ROUTE_PREFIX
                                ),

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
     *   TRUE if annotation is acceptable as Action Annotation and FALSE otherwise
     */
    public function accept() {

        $method = $this -> getInnerIterator() -> current();

        return ( ( $method -> isPublic() && $method -> isFinal() ) &&         # Visibility and Extensibility
                 ( substr( $method -> name, 0, 2 ) !== '__' )      &&         # Constructor, Destructor and other Magicals
                 ( strpos( $method -> class, self::FRAMEWORK ) === FALSE )    # Framework Methods (not always final)
               );
    }

    // Auxiliary Methods

    /**
     * Find Actions Annotations
     *
     * @param ReflectionMethod $action
     *   Action to get Routes Annotations from
     *
     * @param string $annotationPrefix
     *  Annotation prefix used to distinguish an API doc-comment from an Action Annotation
     *
     * @return array
     *   Found Annotations
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
