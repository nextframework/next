<?php

/**
 * Singleton-registry Component Class | Component\Registry.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

/**
 * Defines a Collection of resources perpetuated over the Request Flow
 * through one single instance provided by the Singleton Pattern
 *
 * @package    Next\Components
 */
class Registry {

    /**
     * Registry Instance
     *
     * @staticvar \Next\Components\Registry $_instance
     */
    private static $_instance;

    /**
     * Registry Storage
     *
     * @var array $registry
     */
    private $registry = array();

    /**
     * Enforcing Singleton. Disallow Cloning
     */
    private function __clone() {}

    /**
     * Enforcing Singleton. Disallow Direct Constructor
     */
    private function __construct() {}

    /**
     * Get Registry Instance
     *
     * @return \Next\Components\Registry
     *  Registry Instance
     */
    public static function getInstance() {

        if( NULL === self::$_instance ) {

            self::$_instance = new Registry;
        }

        return self::$_instance;
    }

    /**
     * Add/Set a Registry Entry value
     *
     * @param string $key
     *  Registry Key
     *
     * @param mixed $value
     *  Value to be stored
     *
     * @return \Next\Components\Registry
     *  Registry Object (Fluent Interface)
     */
    public function set( $key, $value ) {

        $key = trim( $key );

        if( ! empty( $key ) ) {

            $this -> registry[ $key ] = $value;
        }

        return $this;
    }

    /**
     * Get a Registry Entry value
     *
     * @param string $key
     *  Registry Key
     *
     * @return mixed
     *  Registry Entry
     */
    public function get( $key ) {

        $key   = trim( $key );

        return ( array_key_exists( $key, $this -> registry ) ? $this -> registry[ $key ] : FALSE );
    }
}
