<?php

namespace Next\DB\Entity;

use Next\Components\Object;               # Object Class
use Next\DB\Table\Manager;

class Repositories extends Object {

    /**
     * Repositories Collection
     *
     * @var array $repositories
     */
    private $repositories = array();

    /**
     * Add a new Entity Repository
     *
     * @param Next\DB\Table\Manager $manager
     *  Table Manager Object
     *
     * @param string  $repository
     *  Repository classname
     *
     * @return Next\DB\Entity\Repositories
     *  Repositories Object (Fluent-Interface)
     */
    public function addRepository( Manager $manager, $repository ) {

        $reflector = new \ReflectionClass( $repository );

        if( $reflector -> isSubclassOf( 'Next\DB\Entity\Repository' ) ) {
            $this -> repositories[ $reflector -> getShortName() ] = $reflector -> newInstance( $manager );
        }

        return $this;
    }

    /**
     * Get an Entity Repository
     *
     * @param  string $repository
     *  Entity Repository to retrieve
     *
     * @return Next\DB\Entity\Repository|NULL
     *  The Entity Repository Object if found and NULL otherwise
     */
    public function getRepository( $repository ) {

        if( strpos( $repository, '\\' ) !== FALSE ) {
            $repository = implode( '', array_slice( explode( '\\', $repository ), -1 ) );
        }

        return ( array_key_exists( $repository, $this -> repositories ) ? $this -> repositories[ $repository ] : NULL );
    }
}
