<?php

/**
 * Routes Generators Classes' Actions Annotations Class | Controller\Router\Generators\Annotations\Actions.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router\Generators\Annotations;

use Next\Tools\Routes\Generators\GeneratorsException;    # Routes Generators Exception Class
use Next\Components\Utils\ArrayUtils;                    # Array Utils Class

/**
 * Defines the Controller Actions Analyzer, filtering through data
 * reflected and preparing structure for the Routes Generator process
 *
 * @package    Next\Tools\Routes\Generators
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
     * Reflection Object with Controllers to get Methods from
     *
     * @var ReflectionClass $reflector
     */
    protected $reflector;

    /**
     * Controller Actions Annotations Constructor
     *
     * @param \ReflectionClass $reflector
     *  ReflectionClass Object from where to retrieve the Action Methods and build an \ArrayIterator with
     */
    public function __construct( \ReflectionClass $reflector ) {

        $this -> reflector = $reflector;

        parent::__construct( new \ArrayIterator( $reflector -> getMethods() ) );

        /**
         * @see https://bugs.php.net/bug.php?id=52560
         */
        $this -> rewind();
    }

    // Annotations Interface Method Implementation

    /**
     * Get Annotations Found
     *
     * @return array
     *  Found annotations
     *
     * @throws \Next\Tools\Routes\Generators\GeneratorsException
     *  Route argument has less than 2 Components (a Name and a Type)
     */
    public function getAnnotations() {

        $data = [];

        $labels = [ 'name', 'type', 'acceptable', 'default', 'regex' ];

        foreach( $this as $current ) {

            // Preparing Arguments Structure right now

            $args = [];

            foreach( $this -> findActionAnnotations( $current, self::ARGS_PREFIX ) as $index => $arg ) {

                $temp = explode( ',', $arg, 5 );

                // Basic Integrity Check

                if( count( $temp )  < 2 ) {

                    throw GeneratorsException::malformedArguments(

                        [ $current -> class, $current -> name ]
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

                $args[] = array_combine( $labels, $temp );
            }

            // Saving Data

            $data[ $current -> name ] = [

                'routes'    =>  $this -> findActionAnnotations( $current, self::ROUTE_PREFIX ),
                'args'      =>  $args
            ];
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
                 ( strpos( $method -> class, self::FRAMEWORK ) === FALSE ) &&

                 /**
                  * @internal
                  *
                  * This is not a user-defined rule!
                  *
                  * This condition ensures that we're listing only methods of
                  * the class being reflected instead of all its parent altogether
                  *
                  * This grants the ability to have a hierarchical structure of
                  * small Controllers one descending to another, not in the
                  * Object Orientation way, but instead more like a skeleton structure
                  */
                 ( $method -> class === $this -> reflector -> getName() )
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
