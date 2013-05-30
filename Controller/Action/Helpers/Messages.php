<?php

namespace Next\Controller\Action\Helpers;

use Next\Session\Environment\EnvironmentException;    # Session Environment Exception Class
use Next\Session\Environment;                         # Session Environment

/**
 * Session Messenger Helper
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Messages {

    // Default Session Environment Name

    /**
     * Default Environment Name
     *
     * @var string
     */
    const ENVIRONMENT    =   'Messages';

    /**
     * Message Appending Mode
     *
     * @var integer
     */
    const APPEND        = 1;

    /**
     * Message Overwriting Mode
     *
     * @var integer
     */
    const OVERWRITE     = 2;

    /**
     * Environment Object
     *
     * @var Next\Session\Environment $environment
     */
    private $environment;

    /**
     * Messages Helper Constructor
     *
     * Creates a Session Environment and sets the working mode
     *
     * @param string|optional $environment
     *   Environment Name
     *
     * @param integer|optional $mode
     *   Working Mode
     */
    public function __construct( $environment = self::ENVIRONMENT, $mode = self::APPEND ) {

        $this -> environment = new Environment( $environment );

        $this -> setMode( $mode );
    }

    /**
     * Add a Message Group
     *
     * A Message Group which means another dimension in Session Environment
     *
     * @param string|optional $group
     *   Index where the messages will be saved in Session Environment
     *
     * @return Next\Controller\Action\Helpers\Messages
     *   Messages Instance (Fluent Interface)
     */
    public function addGroup( $group = 'messages' ) {

        $group = trim( $group );

        if( ! empty( $group ) && ! array_key_exists( $group, $this -> environment ) ) {

            $this -> environment -> {$group} = array();
        }

        return $this;
    }

    /**
     * Add a new Message
     *
     * @param mixed $message
     *   Message to add
     *
     * @param string|optional $group
     *   A group to store messages
     *
     * @return Next\Controller\Action\Helpers\Messages
     *   Messages Instance (Fluent Interface)
     *
     * @throws Next\Controller\Action\Helpers\HelpersException
     *   A Next\Session\Environment\EnvironmentException is caught when
     *   Next\Session\Environment::append() is invoked
     *
     * @see Next\Session\Environment\EnvironmentException
     * @see Next\Environment::append()
     */
    public function addMessage( $message, $group = 'messages' ) {

        // Working in 'append' mode

        if( $this -> mode == self::APPEND ) {

            // Adding Message's Group if it doesn't exists in Environment

            $this -> addGroup( $group );

            // Checking if it's already an array

            if( ! is_array( $this -> environment -> {$group} ) ) {

                // It's not an array, let's converting it to array...

                $this -> environment -> {$group} = (array) $this -> environment -> {$group};
            }

            // Now we can append the message

            try {

                $this -> environment -> append( $group, $message );

            } catch( EnvironmentException $e ) {

                throw new HelpersException( $e -> getMessage() );
            }

        } else {

            // Working as 'overwrite' mode

            $this -> environment -> {$group} = $message;
        }

        return $this;
    }

    /**
     * Get Messages
     *
     * @return array
     *   Session Environment with stored Messages
     */
    public function getMessages() {

        try {

            $messages = $this -> environment -> getAll();

        } catch( EnvironmentException $e ) {

            $messages = array();
        }

        return $messages;
    }

    /**
     * Purge Messages
     *
     * @return Next\Controller\Action\Helpers\Messages
     *     Messages Instance (Fluent Interface)
     */
    public function purge() {

        $this -> environment -> unsetAll();

        return $this;
    }

    /**
     * Set Working Mode
     *
     * @param integer|optional $mode
     *   Working Mode
     *
     * @return Next\Controller\Action\Helpers\Messages
     *   Messages Helper Instance (Fluent Interface)
     */
    public function setMode( $mode = self::APPEND ) {

       $this -> mode = $mode;

       return $this;
    }
}