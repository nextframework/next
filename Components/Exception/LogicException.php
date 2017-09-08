<?php

namespace Next\Components\Exception;

use Next\Debug\Exception\Exception;    # Exception Class

class LogicException extends Exception {

    public static function missing( $option ) {

        return new self(

            sprintf(
                'Missing required Parameter Option <strong>%s</strong>', $option
            )
        );
    }
}