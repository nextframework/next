<?php

namespace Next\Exception\Exceptions;

use Next\Exception\Exception;    # Exception Class

class InvalidArgumentException extends Exception {

    public static function type( $option, $type ) {

        return new self(

            sprintf(

                'Parameter Option <strong>%1$s</strong> must be an instance of <em>%2$s</em>',

                $option, $type
            )
        );
    }
}