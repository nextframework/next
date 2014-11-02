<?php

namespace Next\Tools\ClassMapper;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Components\Object;                        # Object Class
use Next\Components\Parameter;                     # Parameter Object

/**
 * Abstract Mapper Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractMapper extends Object implements Parameterizable, Mapper {

    /**
     * Default Options. Must be overwritten
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array();

    /**
     * Output Format Options
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * ClassMapper Constructor
     *
     * @param mixed|optional $options
     *
     *   <br />
     *
     *   <p>List of Options to affect Class Mapper. Acceptable values are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>Associative and multidimensional array</li>
     *
     *           <li>
     *
     *               An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *           </li>
     *
     *           <li>A well formed Parameter Object</li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>There are no Common Options defined so far.</p>
     *
     *   <p>
     *     All the arguments taken in consideration are defined in (and by)
     *     concrete classes
     *   </p>
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        // Setting Up Options Object

        $this -> options = new Parameter( $this -> defaultOptions, $this -> setOptions(), $options );

        // Checking Options Integrity

        $this -> checkIntegrity();

        // Extra Initialization

        $this -> init();
    }

    /**
     *    Extra Initialization
     *
     * Class Mapper Strategy additional initialization. Must be overwritten
     */
    protected function init() {}

    // Parabeterizable Interface Methods Implementation

    /**
     * Set ClassMapper Options
     *
     * Overwritable because not all Mapping Strategies have specific options
     * to be set
     */
    public function setOptions() {}

    /**
     * Get Header Fields Options
     *
     * @return Next\Components\Parameter
     *  Parameter Object with merged options
     */
    public function getOptions() {
        return $this -> options;
    }

    // Abstract Methods Defintion

    /**
     * Checks Options Integrity
     *
     * It's abstract because each Builder should check in a different way, different types of options
     */
    abstract protected function checkIntegrity();
}
