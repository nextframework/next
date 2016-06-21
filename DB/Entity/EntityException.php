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
     * Nothing to Update
     *
     * @var integer
     */
    const NO_TABLE_OBJECT       = 0x00000330;

    /**
     * Invalid Repository
     *
     * @var integer
     */
    const INVALID_REPOSITORY      = 0x00000331;

    /**
     * Repository not exist
     *
     * @var integer
     */
    const REPOSITORY_NOT_EXISTS    = 0x00000332;

    // Exception Messages

    /**
     * No Table Object defined
     *
     * @return Next\DB\Entity\EntityException
     *  No Table Object was defined
     */
    public static function noTableObject() {

        return new self(

            'No Table Object defined!

            <p>
                If you\'re using an Entity Repository, remember to call
                <em>Next\DB\Table\Manager::setTable()</em> in <em>Next\DB\Entity\Repository::init()</em>
                (inherited from <em>Next\Components\Object</em>) or by cautiously overwriting its Constructor.
            </p>

            <p>
                If you\'re NOT using an Entity Repository, remember to provide a
                valid Table Object as second argument of <em>Next\DB\Table\Manager</em>.
            </p>',

            self::NO_TABLE_OBJECT
        );
    }

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

            self::INVALID_REPOSITORY, $repositoryClass
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

            self::REPOSITORY_NOT_EXISTS, $repositoryClass
        );
    }
}