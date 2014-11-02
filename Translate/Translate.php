<?php

namespace Next\Translate;

use Next\Translate\TranslateException;    # Translate Exception Class
use Next\Translate\Adapter\Adapter;       # Translate Adapters Interface
use Next\Components\Object;               # Object Class
use Next\File\Tools;                      # File Tools Class
use Next\HTTP\Stream\Adapter\Socket;      # HTTP Stream Socket Adapter Class

/**
 * Translator Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Translator extends Object {

    /**
     * Translate Adapter
     *
     * @var Next\HTTP\Stream\Adapter\Adapter $adapter
     */
    private $adapter;

    /**
     * Current Locale
     *
     * @var string $locale
     */
    private $locale = 'en_US';

    /**
     * Registered Locales Files Contents
     *
     * @var array $data
     */
    private static $data = array();

    /**
     * Current Table
     *
     * @var array $current
     */
    private $current = array();

    /**
     * Set Translation Adapter
     *
     * @param Next\Translate\Adapter\Adapter $adapter
     *  Translate Adapter
     *
     * @return Next\Translate
     *  Translate Object (Fluent Interface)
     */
    public function setAdapter( Adapter $adapter ) {

        $this -> adapter =& $adapter;

        return $this;
    }

    /**
     * Set new Current Locale
     *
     * @param string $locale
     *  Default Locale
     *
     * @return Next\Translate
     *  Translate Object (Fluent Interface)
     */
    public function setLocale( $locale ) {

        $this -> locale =& $locale;

        return $this;
    }

    /**
     * Get Current Locale
     *
     * @return string
     *  Current Locale
     */
    public function getLocale() {
        return $this -> locale;
    }

    /**
     * Choose a File to be used as current Locale File
     *
     * This is because, for example, when working with GetText Adapter
     * (and without a Cache) the reading and parsing operation will be
     * as fast as the file is small
     *
     * With this option you can have several tiny Localization Files,
     * without overload the Application
     *
     * @param string $filename
     *  Translate Filename
     *
     * @return Next\Translate
     *  Translate Object (Fluent Interface)
     */
    public function setCurrent( $filename ) {

        $this -> current =& $filename;

        return $this;
    }

    /**
     * Add a new Language Set
     *
     * @param string $locale
     *  Locale Name
     *
     * @param string $filename
     *  Path to Localization File
     *
     * @return Next\Translate
     *  Translate Object (Fluent Interface)
     *
     * @throws Next\Translate\TranslateException
     *  Given filepath doesn't exists
     *
     * @throws Next\Translate\TranslateException
     *  Given filepath is not readable
     *
     * @throws Next\Translate\TranslateException
     *  No Translation Adapter was provided
     */
    public function addLang( $locale, $filename ) {

        if( ! $this -> adapter instanceof Adapter ) {
            throw TranslateException::noAdapter();
        }

        $info = new \SplFileInfo( $filename );

        if( ! $info -> isFile() ) {
            throw TranslateException::fileNotFound( $filename );
        }

        if( ! $info -> isReadable() ) {
            throw TranslateException::fileNotReadable( $filename );
        }

        // Setting Up Adapter Stream

        $this -> adapter -> setStream( new Socket( $filename, 'r' ) );

        // Adding Locale File

        $content = $this -> adapter -> getTranslationTable( $filename );

        self::$data[ basename( $filename ) ][ $locale ] = $content;

        return $this;
    }

    /**
     * Return given string translated
     *
     * @param string $string
     *  String to be translated
     *
     * @return string
     *  Translated sentence
     */
    public function __( $string ) {

        $source =& self::$data[ $this -> current ][ $this -> locale ];

        if( ! array_key_exists( $string,  $source ) ) {
            return $string;
        }

        return $source[ $string ];
    }
}
