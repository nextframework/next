<?php

namespace Next\Session\Handlers;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Components\Object;                        # Object Class
use Next\Components\Parameter;                     # Parameter Class

/**
 * Session Handler Abstract Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractHandler extends Object implements Parameterizable, Handler {

    /**
     * Session Handler Default Options
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array(

        'name'        => 'Session',
        'savePath'    => '',
        'lifetime'    => 180
    );

    /**
     * Session Handler Options
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * Session Handlers Constructor
     *
     * @param mixed|optional $options
     *
     *   <br />
     *
     *   <p>
     *       List of Options to affect Session Handlers. Acceptable values are:
     *   </p>
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
     *   <p>The arguments taken in consideration are:</p>
     *
     *   <ul>
     *
     *       <li>
     *
     *           <p><strong>name</strong></p>
     *
     *           <p>The Session Name</p>
     *
     *           <p>Default Value: <strong>Session</strong>.</p>
     *
     *       </li>
     *
     *       <li>
     *
     *           <p><strong>savePath</strong></p>
     *
     *           <p>The Session Save Path</p>
     *
     *           <p>
     *               Default Value: <em><empty></em>
     *               (because of native Session Handler)
     *           </p>
     *
     *       </li>
     *
     *       <li>
     *
     *           <p><strong>lifetime</strong></p>
     *
     *           <p>Maximum lifetime for Sessions</p>
     *
     *           <p>Default Value: <strong>180</strong> (seconds)</p>
     *
     *       </li>
     *
     *   </ul>
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        $this -> options = new Parameter( $this -> defaultOptions, $this -> setOptions(), $options );
    }

    // Parabeterizable Interface Methods Implementation

    /**
     * Get Session Handler Options
     *
     * @return Next\Components\Parameter
     *   Parameter Object with merged options
     */
    public function getOptions() {
        return $this -> options;
    }
}
