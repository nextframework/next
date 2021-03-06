<?php

/**
 * Prototype Component Class | Components\Prototype.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\BadMethodCallException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Types\Type;                 # Data-type Interface
use Next\Components\Interfaces\Prototypical;    # Prototypical Interface
use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Components\Utils\ArrayUtils;           # ArrayUtils Class

/**
 * Base structure of a Prototypical Object that allows new functionalities to be
 * prototyped to them -AND- be available to future instances as well —
 * considering the vertically crescent Request Flow, of course — similarly to
 * JavaScript Prototypes
 *
 * @package    Next\Components
 *
 * @uses       Next\Exception\Exceptions\BadMethodCallException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Types\Type
 *             Next\Components\Interfaces\Prototypical
 *             Next\Components\Interfaces\Prototypable
 *             Next\Components\Utils\ArrayUtils
 */
abstract class Prototype implements Prototypical {

    /**
     * Registered Prototypes
     *
     * @var array $prototypes
     */
    private static $prototypes = [];

    /**
     * Implement a new callable resource, prototyping it to Object
     *
     * @param \Next\Components\Object|string
     *  The name of the Object receiving the prototyped resource
     *
     * @param string $name
     *  Callable resource name. Should be unique
     *
     * @param callable $callable
     *  Callable resource
     *
     * @param array $args
     *  Default arguments available to callable resource
     *
     * @return \Next\Components\Prototype
     *  Prototype Instance (Fluent Interface)
     */
    public function implement( $prototype, $name, $callable, $args = [] ) : Prototype {

        $prototype = ( is_object( $prototype ) ? get_class( $prototype ) : (string) $prototype );

        self::$prototypes[ $prototype ][ (string) $name ] = [ $callable, (array) $args ];

        return $this;
    }

    /**
     * Invoke a prototyped resource from a caller context
     *
     * @param \Next\Components\Object|string $caller
     *  Caller Object name.
     *
     * @param string $method
     *  Callable resource name
     *
     * @param array $args
     *  Calling Arguments
     *
     * @return mixed
     *  If Caller Object is an instance of Next\Components\Types\Type
     *  -AND- the Prototype Call results in something the Caller Object
     *  accepts — i.e Caller Object is an instance of
     *  \Next\Components\Types\Strings Type and the result is also a
     *  string — a new copy of the Caller Object is returned but with
     *  the resulting value.
     *
     *  If an Next\Exception\Exceptions\InvalidArgumentException
     *  is caught — meaning the data-type class didn't accept the
     *  result of the Prototyped resource — the resulting value is
     *  returned "as is"
     *
     *  If the the result is not an instance of Next\Components\Types\Type
     *  it'll be returned "as is" as well
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Thrown if called Prototyped Resource is a string pointing to a not found
     *  Class raising a ReflectionException
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Called resource is not known as a Prototyped Resource nor as
     *  part of an Extended Context
     */
    public static function call( $caller, $method, array $args = [] ) {

        $prototype = ( is_object( $caller ) ? get_class( $caller ) : (string) $caller );

        if( isset( self::$prototypes[ $prototype ][ $method ] ) ) {

            $prototype = self::$prototypes[ $prototype ][ $method ];

            // Merging always optional arguments with called arguments

            if( count( $args ) > 0 ) {

                ArrayUtils::insert( $prototype[ 1 ], $args );

            } else {

                // Nothing to Merge? OK!

                $args = $prototype[ 1 ];
            }

            // Classnames

            if( is_string( $prototype[ 0 ] ) && ! is_callable( $prototype[ 0 ] ) ) {

                try {

                    $reflector = new \ReflectionClass( $prototype[ 0 ] );

                    $prototype[ 0 ] = $reflector -> newInstance();

                } catch( \ReflectionException $e ) {

                    throw new BadMethodCallException(
                        $e -> getMessage(), BadMethodCallException::PHP_ERROR
                    );
                }
            }

            // Well-formed Prototypable Object

            if( $prototype[ 0 ] instanceof Prototypable ) {

                $result = $prototype[ 0 ] -> prototype( $args );

            } else {

                // Closures, internal functions and general callables

                $result = call_user_func_array( $prototype[ 0 ], $args );
            }

            try {

                if( $caller instanceof Type ) {
                    return new $caller( [ 'value' => $result ] );
                }

                return $result;

            } catch( InvalidArgumentException $e ) {

                return $result;
            }
        }

        throw new BadMethodCallException(

            sprintf(

                'Method <strong>%s</strong> could not be matched against
                any methods in extended Context or prototyped functions',

                $method
            )
        );
    }

    /**
     * Get Prototyped Resources
     *
     *  @return array
     *    All prototyped resources
     */
    public function getPrototypes() : array {
        return self::$prototypes;
    }
}