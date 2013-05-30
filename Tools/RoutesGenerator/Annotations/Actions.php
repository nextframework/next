<?php

namespace Next\Tools\RoutesGenerator\Annotations;

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

            $routes = $this -> matchRouteAnnotations( $current );

            $routes = ( count( $routes ) > 1 ? $routes : array_shift( $routes ) );

            $data[ $current -> class ][ $current -> name ] = $routes;
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
     * Match Route Annotations
     *
     * Routes Annotations start with !Route
     *
     * @param ReflectionMethod $action
     *   Action to get Routes Annotations from
     *
     * @return array
     *   Found Annotations
     */
    private function matchRouteAnnotations( \ReflectionMethod $action ) {

        $routes = preg_grep(

            sprintf( '/%s/', self::ROUTE_PREFIX ),

            preg_split('/[\n\r]+/', $action -> getDocComment() )
        );

        return preg_replace( sprintf( '/.*?%s\s*/', self::ROUTE_PREFIX ), '', $routes );
    }
}
