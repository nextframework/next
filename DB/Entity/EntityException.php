<?php

namespace Next\DB\Entity;

/**
 * Entity Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class EntityException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000330, 0x00000362 );

    /**
     * Invalid Repository
     *
     * @var integer
     */
    const INVALID_REPOSITORY      = 0x00000330;

    /**
     * Repository not exist
     *
     * @var integer
     */
    const REPOSITORY_NOT_EXISTS    = 0x00000331;

    /**
     * Missing Entity Manager
     *
     * @var integer
     */
    const NO_ENTITY_MANAGER         = 0x00000332;

    /**
     * No Entity
     *
     * @var integer
     */
    const NO_ENTITY                = 0x00000333;

    // Exception Messages

    /**
     * Invalid Repository Class
     *
     * @param string $repositoryClass
     *  Repository Classname
     *
     * @return Next\DB\Entity\EntityException
     *  Invalid Repository Class
     */
    public static function invalidRepository( $repositoryClass ) {

        return new self(

            'Informed repository Class <strong>%s</strong> is not valid.

            All Repository Classes must be an instance of Next\\DB\Entity\Repository',

            self::INVALID_REPOSITORY, array( $repositoryClass )
        );
    }

    /**
     * Repository doesn't exist
     *
     * @param string $repositoryClass
     *  Repository Classname
     *
     * @return Next\DB\Entity\EntityException
     *  Repository Class doesn't exist
     */
    public static function repositoryNotExists( $repositoryClass ) {

        return new self(

            'Repository Class <strong>%s</strong> doesn\'t exist',

            self::REPOSITORY_NOT_EXISTS, array( $repositoryClass )
        );
    }

    /**
     * Missing Entity Manager
     *
     * @return  Next\DB\Entity\EntityException
     *  Missing Entity Manager
     */
    public function noEntityManager() {
        return self( 'Missing Entity Manager', self::NO_ENTITY_MANAGER );
    }

    /**
     * No Entity
     *
     * @return Next\DB\Entity\EntityException
     *  No Entity defined
     */
    public static function noEntity() {
        return new self( 'Missing Entity', self::NO_ENTITY );
    }
}