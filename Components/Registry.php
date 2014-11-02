<?php

namespace Next\Components;

/**
 * Registry Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Registry {

    /**
     * Registry Instance
     *
     * @staticvar Next\Components\Registry $_instance
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
     * @return Next\Components\Registry
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
     * @return Next\Components\Registry
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
