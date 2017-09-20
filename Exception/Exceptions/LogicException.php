<?php

namespace Next\Exception\Exceptions;

use Next\Exception\Exception;    # Exception Class

class LogicException extends Exception {

    public static function missing( $option ) {

        return new self(

            sprintf(
                'Missing required Parameter Option <strong>%s</strong>', $option
            )
        );
    }
}